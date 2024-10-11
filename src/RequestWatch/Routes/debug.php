<?php

use YonisSavary\Sharp\Classes\Web\Route;
use YonisSavary\Sharp\Classes\Web\Router;
use YonisSavary\Sharp\Core\Utils;
use YonisSavary\Sharp\Extensions\RequestWatch\Classes\RequestWatch;

if (!Utils::isProduction())
{
    Router::getInstance()->addRoutes(
        Route::get("/request-watch/tree/{int:id}", function($_, int $id){
            return RequestWatch::getUserNavigationTree($id);
        })
    );
}