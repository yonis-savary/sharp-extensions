<?php

namespace SharpExtensions\RemindMe\Components;

use Sharp\Classes\Core\Component;
use Sharp\Classes\Core\Configurable;
use Sharp\Classes\Env\Cache;
use Sharp\Classes\Env\Configuration;
use Sharp\Classes\Security\Authentication;
use Sharp\Classes\Data\Model;

class RemindMe
{
    use Component, Configurable;

    public static function getDefaultConfiguration(): array
    {
        return [
            "cookie-name" => "sharp_extensions_remind_me",
            "cookie-duration" => Cache::DAY * 14,
            "same-ip-required" => false
        ];
    }

    protected function getCache(): Cache
    {
        return Cache::getInstance()->getSubCache("remind-me");
    }

    public function __construct(Configuration $configuration = null)
    {
        $configuration ??= Configuration::getInstance();

        $this->loadConfiguration($configuration);
    }

    public function getClientIP(): string
    {
        $ip = $_SERVER["REMOTE_ADDR"];
        return preg_replace("/[^0-9]/", "_", $ip);
    }

    public function rememberLoggedUser(): bool
    {
        $authentication = Authentication::getInstance();

        if (!$authentication->isLogged())
            return false;

        $model = $authentication->model;
        $primaryKey = $model::getPrimaryKey();

        $userId = $authentication->getUser()["data"][$primaryKey];

        $ip = $this->getClientIP();
        $token = bin2hex(random_bytes(64));

        $cache = $this->getCache();
        $cache->set($token, ["token" => $token, "ip" => $ip, "user-id" => $userId]);

        setcookie(
            $this->configuration["cookie-name"],
            $token,
            time() + $this->configuration["cookie-duration"],
            httponly: true
        );

        return true;
    }

    public function forgetLoggedUser(): void
    {
        $ip = $this->getClientIP();
        $cache = $this->getCache();
        $keys = $cache->getKeys();

        foreach ($keys as $key)
        {
            if (!$data = $cache->try($key))
                continue;

            if ($data["ip"] === $ip)
                $cache->delete($key);
        }
    }

    public function tryToRemember(): bool
    {
        $userSideToken = $_COOKIE[$this->configuration["cookie-name"]] ?? false;
        $userIP = $this->getClientIP();

        $cache = $this->getCache();

        if (!$data = $cache->try($userSideToken))
            return false;

        if ($this->configuration["same-ip-required"] && ($data["ip"] != $userIP))
            return false;

        $authentication = Authentication::getInstance();
        /** @var Model $model */
        $model = $authentication->model;
        $authentication->login($model::findId($data["user-id"]));

        return true;
    }
}