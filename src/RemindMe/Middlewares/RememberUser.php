<?php

namespace YonisSavary\Sharp\Extensions\RemindMe\Middlewares;

use YonisSavary\Sharp\Classes\Http\Request;
use YonisSavary\Sharp\Classes\Http\Response;
use YonisSavary\Sharp\Classes\Security\Authentication;
use YonisSavary\Sharp\Classes\Web\MiddlewareInterface;
use YonisSavary\Sharp\Extensions\RemindMe\Components\RemindMe;

class RememberUser implements MiddlewareInterface
{
    const REMINDED_REDIRECT = "/";

    public static function handle(Request $request): Request|Response
    {
        $authentication = Authentication::getInstance();
        if ($authentication->isLogged())
            return $request;

        $remindMe = RemindMe::getInstance();
        $remindMe->tryToRemember();

        return $request;
    }
}