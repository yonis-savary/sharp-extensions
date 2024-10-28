<?php

/**
 * This script purpose is to be an alternative to /Sharp/bootstrap.php
 * The goal is to make a good envrionment to test with (with Database, Configuration...etc)
 * ------------------------------------------------
 */

use YonisSavary\Sharp\Classes\Core\Logger;
use YonisSavary\Sharp\Classes\Data\Database;
use YonisSavary\Sharp\Classes\Env\Cache;
use YonisSavary\Sharp\Classes\Env\Configuration;
use YonisSavary\Sharp\Classes\Env\Storage;
use YonisSavary\Sharp\Classes\Test\SharpServer;
use YonisSavary\Sharp\Core\Autoloader;
use YonisSavary\Sharp\Core\Utils;

if (! $GLOBALS['sharp-src']  = realpath(__DIR__ . '/../vendor/yonis-savary/sharp/src'))
    die("Could not define sharp source directory");

if (! $GLOBALS['sharp-root'] = realpath(__DIR__ . "/Root"))
    die("Could not define sharp-root directory.");

require_once __DIR__ . '/../vendor/autoload.php';

Autoloader::initialize();

$rootDirectory    = new Storage($GLOBALS['sharp-root']);
$testLogger       = new Logger('test-suite.csv', new Storage(__DIR__));
$testStorage      = new Storage($rootDirectory->path('/Storage'));
$testConfig       = new Configuration($rootDirectory->path('/sharp.json'));
$cacheStorage     = new Cache($testStorage->getSubStorage('Cache'));

// $testLogger->debug('Starting test suite');
// $testLogger->debug('Sharp root directory : {dir}', ['dir' => $GLOBALS['sharp-root']]);
// $testLogger->debug('Sharp src directory : {dir}', ['dir' => $GLOBALS['sharp-src']]);

Autoloader::loadApplication(Utils::relativePath("TestApp"));

Logger::setInstance($testLogger);
Storage::setInstance($testStorage);
Configuration::setInstance($testConfig);
Cache::setInstance($cacheStorage);

Database::setInstance(
    new Database("sqlite", null)
);

register_shutdown_function(function(){
    if (!SharpServer::hasInstance())
        return;

    $server = SharpServer::getInstance();

    $logger = Logger::getInstance();
    $logger->info($server->getOutput());
    $logger->info($server->getErrorOutput());
});