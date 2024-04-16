<?php

namespace SharpExtensions\Boiler\Packages\Authentication\Controllers;

use Sharp\Classes\Http\Request;
use Sharp\Classes\Http\Response;
use Sharp\Classes\Security\Authentication;
use Sharp\Classes\Web\Controller;
use Sharp\Classes\Web\Route;
use Sharp\Classes\Web\Router;
use SharpExtensions\Boiler\Packages\Authentication\Classes\Straws\UserData;

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