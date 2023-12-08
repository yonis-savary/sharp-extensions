<?php


namespace SharpExtensions\RemindMe\Middlewares;

use Sharp\Classes\Http\Request;
use Sharp\Classes\Http\Response;
use Sharp\Classes\Security\Authentication;
use Sharp\Classes\Web\MiddlewareInterface;
use SharpExtensions\RemindMe\Components\RemindMe;

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