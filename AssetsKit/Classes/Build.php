<?php

namespace SharpExtensions\AssetsKit\Classes;

use Sharp\Classes\CLI\AbstractBuildTask;
use Sharp\Classes\Data\ObjectArray;
use Sharp\Classes\Env\Cache;
use Sharp\Classes\Env\Storage;
use Sharp\Core\Utils;

class Build extends AbstractBuildTask
{
    public function execute()
    {
        $assetsKitDir = Utils::relativePath('SharpExtensions/AssetsKit');

        $this->log("Building stylesheet...\n");
        $styleDir = Utils::joinPath($assetsKitDir, '/Assets/less');

        $command = str_starts_with(PHP_OS, "WIN") ? "lessc.cmd" : "lessc";

        $this->shellInDirectory("$command main.less ../css/assets-kit/style.css --verbose", $styleDir, true);
        $this->shellInDirectory("$command essentials.less ../css/assets-kit/essentials.css --verbose", $styleDir, true);


        $this->log("Bundling scripts...\n");
        $scriptDir = new Storage(Utils::joinPath($assetsKitDir, "/Assets/js/assets-kit"));

        $bundleContent = ObjectArray::fromArray(ASSETS_KIT_BUNDLE_SCRIPTS)
        ->map(fn(string $file) => $scriptDir->read($file))
        ->join("\n\n/* SCRIPT END */\n\n");

        $cache = new Storage(Utils::joinPath($assetsKitDir, "Assets/Cache/js"));
        $cache->write(ASSETS_KIT_BUNDLE_FILE_NAME, $bundleContent);

    }
}