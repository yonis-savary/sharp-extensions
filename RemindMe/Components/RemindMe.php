<?php

namespace SharpExtensions\RemindMe\Components;

use RuntimeException;
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
            "cookie-duration" => Cache::DAY * 14
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

        $authConfig = $configuration->get("authentication");
        if (!$authConfig["enabled"])
            throw new RuntimeException("Cannot use RemindMe as Authentication is not configured !");
    }

    public function remindLoggedUser(): bool
    {
        $authentication = Authentication::getInstance();

        if (!$authentication->isLogged())
            return false;

        $model = $authentication->model;
        $primaryKey = $model::getPrimaryKey();

        $userId = $authentication->getUser()["data"][$primaryKey];

        $ip = $_SERVER["REMOTE_ADDR"];
        $token = bin2hex(random_bytes(64));

        $cache = $this->getCache();
        $cache->set($ip, ["token" => $token, "user-id" => $userId]);

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
        $ip = $_SERVER["REMOTE_ADDR"];

        $cache = $this->getCache();
        $cache->delete($ip);
    }

    public function tryToRemember(): bool
    {
        $userSideToken = $_COOKIE[$this->configuration["cookie-name"]] ?? false;
        $userIP = $_SERVER["REMOTE_ADDR"];

        $cache = $this->getCache();

        if (!$data = $cache->try($userIP))
            return false;

        if ($data["token"] !== $userSideToken)
            return false;

        $authentication = Authentication::getInstance();
        /** @var Model $model */
        $model = $authentication->model;
        $authentication->login($model::findId($data["user-id"]));

        return true;
    }
}