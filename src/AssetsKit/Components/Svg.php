<?php

namespace YonisSavary\Sharp\Extensions\AssetsKit\Components;

use Exception;
use YonisSavary\Sharp\Classes\Core\Component;
use YonisSavary\Sharp\Classes\Core\Configurable;
use YonisSavary\Sharp\Classes\Http\Request;
use YonisSavary\Sharp\Classes\Http\Response;
use YonisSavary\Sharp\Classes\Web\Route;
use YonisSavary\Sharp\Classes\Env\Cache;
use YonisSavary\Sharp\Core\Utils;

/**
 * This class is the implementation of SVGInterface,
 * it can also be configured with these keys:
 * - enabled : is the component supposed to serve its purpose ?
 * - path : relative path from your project directory to your svg collection
 * - cached : should requested icons be cached ?
 */
class Svg
{
    use Component,Configurable;

    const CACHE_KEY = 'components.svg.cache';
    protected $cache = [];

    public static function getDefaultConfiguration(): array
    {
        return [
            'enabled' => true,
            "url" => "/assets/svg",
            'path' => null,
            'cached' => true,
            'default-size' => 24,
            'max-age' => 3600*24*7, // 1 Weeks
            "middlewares" => []
        ];
    }

    public static function getKeyToLoad(): string
    {
        return 'svg';
    }

    public static function initialize()
    {
        $instance = self::getInstance();
        if ($instance->isEnabled())
            $instance->handleRequest( Request::fromGlobals() );
    }

    public function __construct()
    {
        $this->loadConfiguration();

        $path = $this->configuration['path'];

        if (!$path)
            $path = Utils::relativePath("vendor/twbs/bootstrap-icons/icons");
        else
            $path = Utils::relativePath($path);

        $this->configuration['path'] = $path;

        // Code here will be executed the first time SVG component will be called
        $this->cache = Cache::getInstance()->getReference(self::CACHE_KEY, []);
    }

    public function handleRequest(Request $req, bool $returnResponse=false)
    {
        $url = $this->configuration["url"];
        $route = Route::get($url, [$this, "serve"], $this->configuration["middlewares"]);

        if (!$route->match($req))
            return;

        $response = $route($req);

        if ($age = $this->configuration["max-age"])
            $response->withHeaders(["Cache-Control" => "max-age=$age"]);

        if ($returnResponse)
            return $response;

        $response->display();
        die;
    }

    // ---------- internal methods ----------

    protected function getCollectionPath(): string
    {
        return $this->configuration['path'];
    }

    protected function getIconPath(string $name): string
    {
        return Utils::joinPath($this->getCollectionPath(), $name . '.svg');
    }

    protected function loadSVGFromName(string $name)
    {
        $path = $this->getIconPath($name);

        if (!file_exists($path))
            throw new Exception('File not found !');

        $content = file_get_contents($path);

        if ($this->configuration['cached'] === true)
            $this->cache[$name] = $content;

        return $content;
    }

    // ---------- implementation ----------


    public function get(string $name, int $size=null): string|null
    {
        $content = $this->cache[$name] ?? $this->loadSVGFromName($name);

        $size ??= $this->configuration["default-size"];

        if ($size !== null)
            $content = preg_replace('/(height|width)=".*?"/', "$1='$size'", $content);

        return $content;
    }

    public function exists(string $name): bool
    {
        return file_exists($this->getIconPath($name));
    }

    public function serve(Request $req): Response
    {
        if ($this->getConfiguration()['enabled'] !== true)
            return Response::json('Disabled component', 400);

        list($name, $size) = $req->list('name', 'size');
        if ($name === null)
            return Response::json('"name" parameter is needed !', 400);

        $content = $this->get($name, $size);

        $res = Response::html($content);
        $res->withHeaders([
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'max-age='.(3600*24),
        ]);

        header_remove('Pragma');
        return $res;
    }
}
