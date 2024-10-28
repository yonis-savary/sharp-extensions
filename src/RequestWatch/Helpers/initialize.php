<?php

use YonisSavary\Sharp\Classes\Core\EventListener;
use YonisSavary\Sharp\Classes\Env\Configuration;
use YonisSavary\Sharp\Classes\Events\LoadedFramework;
use YonisSavary\Sharp\Classes\Events\RoutedRequest;
use YonisSavary\Sharp\Classes\Http\Request;
use YonisSavary\Sharp\Extensions\RequestWatch\Classes\RequestWatch;

EventListener::getInstance()->on(LoadedFramework::class, function(){
    $configuration = Configuration::getInstance();
    if (($configuration->get("request-watcher", ["enabled" => false])["enabled"] ?? false) === false)
        return;

    $watch = RequestWatch::getInstance();
    $watch->registerRequest(Request::fromGlobals());

    EventListener::getInstance()->on(RoutedRequest::class, function(RoutedRequest $event) use (&$watch){
        $watch->registerRoute($event->route);
    });
});