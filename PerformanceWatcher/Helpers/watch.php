<?php

use Sharp\Classes\Core\EventListener;
use Sharp\Classes\Core\Logger;
use Sharp\Classes\Events\DispatchedEvent;
use Sharp\Classes\Http\Request;

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
