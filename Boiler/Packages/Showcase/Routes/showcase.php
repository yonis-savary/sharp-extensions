<?php

use Sharp\Classes\Web\Route;
use Sharp\Classes\Web\Router;

Router::getInstance()->addGroup(
    [],
    Route::view("/", "Showcase/home"),
    Route::view("/about", "Showcase/about"),
    Route::view("/contact", "Showcase/contact"),
);