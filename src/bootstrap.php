<?php

use YonisSavary\Sharp\Classes\Core\EventListener;
use YonisSavary\Sharp\Classes\Events\LoadingFramework;
use YonisSavary\Sharp\Core\Autoloader;

EventListener::getInstance()->on(LoadingFramework::class, function(){
    foreach (scandir(__DIR__) as $directory)
    {
        if ($directory == "." || $directory == ".." || $directory == "bootstrap.php")
            continue;

        Autoloader::loadApplication( __DIR__ . "/$directory");
    }
});
