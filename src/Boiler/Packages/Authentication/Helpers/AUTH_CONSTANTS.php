<?php

use YonisSavary\Sharp\Extensions\Boiler\Packages\Authentication\Middlewares\IsLoggedMiddlewares;

if (defined("AUTH_ROUTES"))
    define("AUTH_ROUTES", ["middlewares" => IsLoggedMiddlewares::class]);