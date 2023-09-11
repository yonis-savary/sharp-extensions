<?php

use Sharp\Classes\Web\Route;
use Sharp\Core\Utils;

if (!Utils::isProduction())
{
    addRoutes(
        Route::view("/assetskit", "assetskit/demo")
    );
}