<?php

namespace YonisSavary\Sharp\Extensions\AssetsKit\Classes;

use YonisSavary\Sharp\Classes\CLI\AbstractBuildTask;
use YonisSavary\Sharp\Core\Utils;

class BuildAssetsKitStylesheet extends AbstractBuildTask
{
    public function execute(): int
    {
        $assetsKitDir = realpath( __DIR__ . "/..");

        $this->log("Building stylesheet...\n");
        $styleDir = Utils::joinPath($assetsKitDir, '/Assets/less');

        $command = str_starts_with(PHP_OS, "WIN") ? "lessc.cmd" : "lessc";

        $this->shellInDirectory("$command main.less ../css/assets-kit/style.css --verbose", $styleDir, true);
        $this->shellInDirectory("$command essentials.less ../css/assets-kit/essentials.css --verbose", $styleDir, true);

        return 0;
    }

    public function getWatchList(): array
    {
        $assetsKitDir = realpath( __DIR__ . "/..");
        $styleDir = Utils::joinPath($assetsKitDir, '/Assets/less');

        return [$styleDir];
    }
}