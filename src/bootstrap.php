<?php

use YonisSavary\Sharp\Core\Autoloader;

foreach (scandir(__DIR__) as $directory)
{
    if ($directory == "." || $directory == ".." || $directory == "bootstrap.php")
        continue;

    Autoloader::loadApplication( __DIR__ . "/$directory");
}