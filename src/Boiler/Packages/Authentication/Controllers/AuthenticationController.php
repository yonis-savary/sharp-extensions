<?php

namespace YonisSavary\Sharp\Extensions\Boiler\Packages\Authentication\Controllers;

use YonisSavary\Sharp\Classes\Http\Request;
use YonisSavary\Sharp\Classes\Http\Response;
use YonisSavary\Sharp\Classes\Security\Authentication;
use YonisSavary\Sharp\Classes\Web\Controller;
use YonisSavary\Sharp\Classes\Web\Route;
use YonisSavary\Sharp\Classes\Web\Router;
use YonisSavary\Sharp\Extensions\Boiler\Packages\Authentication\Classes\Straws\UserData;

class AuthenticationController
{
    use Controller;

    public static function declareRoutes(Router $router)
    {
        $router->addRoutes(
            Route::view("/login", "Authentication/login"),
            Route::post("/login", [self::class, "handleLogin"]),
            Route::get("/logout", [self::class, "handleLogout"])
        );
    }

    public static function handleLogin(Request $request)
    {
        list($login, $password) = $request->list('login', 'password');

        $auth = Authentication::getInstance();

        if ($auth->attempt($login, $password))
            return Response::view("Authentication/login", ["error" => "bad_credentials"]);

        UserData::set($auth->getUser());
        return Response::redirect("/");
    }

    public static function handleLogout()
    {
        Authentication::getInstance()->logout();
        UserData::unset();

        return Response::redirect("/login");
    }
}