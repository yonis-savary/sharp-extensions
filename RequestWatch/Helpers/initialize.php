<?php

use Sharp\Classes\Core\EventListener;
use Sharp\Classes\Events\RoutedRequest;
use Sharp\Classes\Http\Request;
use SharpExtensions\RequestWatch\Classes\RequestWatch;

$watch = RequestWatch::getInstance();
$watch->registerRequest(Request::buildFromGlobals());

EventListener::getInstance()->on(RoutedRequest::class, function(RoutedRequest $event) use (&$watch){
    $watch->registerRoute($event->route);
});