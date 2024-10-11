<?php

use YonisSavary\Sharp\Classes\Web\Route;
use YonisSavary\Sharp\Classes\Web\Router;

Router::getInstance()->addGroup(
    [],
    Route::view("/", "Showcase/home"),
    Route::view("/about", "Showcase/about"),
    Route::view("/contact", "Showcase/contact"),
);