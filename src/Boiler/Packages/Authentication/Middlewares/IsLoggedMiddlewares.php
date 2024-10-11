<?php

namespace YonisSavary\Sharp\Extensions\Boiler\Packages\Authentication\Middlewares;

use YonisSavary\Sharp\Classes\Http\Request;
use YonisSavary\Sharp\Classes\Http\Response;
use YonisSavary\Sharp\Classes\Security\Authentication;
use YonisSavary\Sharp\Classes\Web\MiddlewareInterface;

class IsLoggedMiddlewares implements MiddlewareInterface
{
    public static function handle(Request $request): Request|Response
    {
        if (Authentication::getInstance()->isLogged())
            return $request;

        return Response::redirect("/login");
    }
}