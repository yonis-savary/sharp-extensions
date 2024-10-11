<?php

use YonisSavary\Sharp\Classes\Extras\AssetServer;
use YonisSavary\Sharp\Extensions\AssetsKit\Components\Svg;

const ASSETS_KIT_BUNDLE_FILE_NAME = "assets-kit-bundle.js";

const ASSETS_KIT_BUNDLE_SCRIPTS = [

    "bridge.js",
    "lang.js",
    "date.js",

    "eventSource.js",
    "fetch.js",
    "svg.js",

    "aside.js",
    "menu.js",
    "overlay.js",
    "component.js",
    "animation.js",

    "highstate.js",
    "validate.js",
    "notify.js",
    "autocomplete.js",
    "entity.js",

    "summary.js",

    "nav.js"
];

function svg(string $name, int $size=null)
{
    return Svg::getInstance()->get($name, $size);
}

function assetsKitJSBundle(bool $inject=false)
{
    $assetsServer = AssetServer::getInstance();
    if ($path = $assetsServer->findAsset(ASSETS_KIT_BUNDLE_FILE_NAME))
    {
        return $inject ?
            "<script>".file_get_contents($path)."</script>":
            "<script src='". AssetServer::getInstance()->getURL(ASSETS_KIT_BUNDLE_FILE_NAME) ."'></script>"
        ;
    }

    $str = "";
    foreach (ASSETS_KIT_BUNDLE_SCRIPTS as $s)
    {
        $str .= $inject ?
            "<script>".file_get_contents(AssetServer::getInstance()->findAsset($s))."</script>":
            "<script src='". AssetServer::getInstance()->getURL($s) ."'></script>"
        ;

    }
    return $str;
}

function assetsKitCSSStyle(bool $inject=false)
{
    $stylePath="assets-kit/style.css";
    $assetsServer = AssetServer::getInstance();

    return $inject ?
    "<style>".file_get_contents($assetsServer->findAsset($stylePath))."</style>":
    "<link rel='stylesheet' href='". $assetsServer->getURL($stylePath) ."'/>";
}

function assetsKitFullBundle(bool $inject=false)
{
    echo
        assetsKitJSBundle($inject) .
        assetsKitCSSStyle($inject);
}