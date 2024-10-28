<?php

use YonisSavary\Sharp\Classes\Core\EventListener;
use YonisSavary\Sharp\Classes\Core\Logger;
use YonisSavary\Sharp\Classes\Env\Configuration;
use YonisSavary\Sharp\Classes\Events\DispatchedEvent;
use YonisSavary\Sharp\Classes\Events\LoadedFramework;

EventListener::getInstance()->on(LoadedFramework::class, function(){
    $configuration = Configuration::getInstance();
    if (($configuration->get("performance-watcher", ["enabled" => false])["enabled"] ?? false) === false )
        return;

    $lastTime = hrtime(true);
    $initialTime = $lastTime;
    $logger = new Logger("performances.csv");

    $logger->info("--- NEW REQUEST ---" . ($_SERVER['REQUEST_METHOD']  ?? "???") . " " . ($_SERVER['REQUEST_URI'] ?? "???"));

    EventListener::getInstance()->on(DispatchedEvent::class, function(DispatchedEvent $event) use (&$lastTime, &$logger, &$initialTime) {
        $thisTime = hrtime(true);
        $logger->info(
            "t:" . str_pad(round(($thisTime-$initialTime)/1000000, 3), 10, " ", STR_PAD_LEFT) . "ms, " .
            "d:" . str_pad(round(($thisTime-$lastTime)/1000000, 3), 10, " ", STR_PAD_LEFT) . "ms, " .
            $event->dispatched->getName()
        );
        $lastTime = $thisTime;
    });
});

