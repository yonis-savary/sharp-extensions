<?php

use YonisSavary\Sharp\Classes\Core\EventListener;
use YonisSavary\Sharp\Classes\Events\RoutedRequest;
use YonisSavary\Sharp\Classes\Http\Request;
use YonisSavary\Sharp\Extensions\RequestWatch\Classes\RequestWatch;

$watch = RequestWatch::getInstance();
$watch->registerRequest(Request::buildFromGlobals());

EventListener::getInstance()->on(RoutedRequest::class, function(RoutedRequest $event) use (&$watch){
    $watch->registerRoute($event->route);
});