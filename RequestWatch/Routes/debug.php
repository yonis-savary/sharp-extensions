<?php

use Sharp\Classes\Web\Route;
use Sharp\Classes\Web\Router;
use Sharp\Core\Utils;
use SharpExtensions\RequestWatch\Classes\RequestWatch;

if (!Utils::isProduction())
{
    Router::getInstance()->addRoutes(
        Route::get("/request-watch/tree/{int:id}", function($_, int $id){
            return RequestWatch::getUserNavigationTree($id);
        })
    );
}