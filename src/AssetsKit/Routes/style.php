<?php

use YonisSavary\Sharp\Classes\Web\Route;
use YonisSavary\Sharp\Core\Utils;

if (!Utils::isProduction())
{
    addRoutes(
        Route::view("/assets-kit-demo", "assets-kit-demos-home")
    );
}