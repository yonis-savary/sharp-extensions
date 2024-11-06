<?php

namespace YonisSavary\Sharp\Extensions\AssetsKit\Classes;

use YonisSavary\Sharp\Classes\CLI\AbstractBuildTask;
use YonisSavary\Sharp\Classes\Data\ObjectArray;
use YonisSavary\Sharp\Classes\Env\Storage;
use YonisSavary\Sharp\Core\Utils;

class BundlingAssetsKitScripts extends AbstractBuildTask
{
    public function execute(): int
    {
        $assetsKitDir = realpath( __DIR__ . "/..");

        $this->log("Bundling scripts...\n");
        $scriptDir = new Storage(Utils::joinPath($assetsKitDir, "/Assets/js/assets-kit"));

        $bundleContent = ObjectArray::fromArray(ASSETS_KIT_BUNDLE_SCRIPTS)
        ->map(fn(string $file) => $scriptDir->read($file))
        ->join("\n\n/* SCRIPT END */\n\n");

        $cache = new Storage(Utils::joinPath($assetsKitDir, "Assets/Cache/js"));
        $cache->write(ASSETS_KIT_BUNDLE_FILE_NAME, $bundleContent);

        return 0;
    }

    public function getWatchList(): array
    {
        $assetsKitDir = realpath( __DIR__ . "/..");
        $scriptDir = Utils::joinPath($assetsKitDir, "/Assets/js/assets-kit");

        return [$scriptDir];
    }
}