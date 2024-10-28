<?php

namespace YonisSavary\Sharp\Extensions\AssetsKit\Classes;

use YonisSavary\Sharp\Classes\CLI\AbstractBuildTask;
use YonisSavary\Sharp\Core\Utils;

class BuildAssetsKitStylesheet extends AbstractBuildTask
{
    public function execute(): bool
    {
        $assetsKitDir = realpath( __DIR__ . "/..");

        $this->log("Building stylesheet...\n");
        $styleDir = Utils::joinPath($assetsKitDir, '/Assets/less');

        $command = str_starts_with(PHP_OS, "WIN") ? "lessc.cmd" : "lessc";

        $this->shellInDirectory("$command main.less ../css/assets-kit/style.css --verbose", $styleDir, true);
        $this->shellInDirectory("$command essentials.less ../css/assets-kit/essentials.css --verbose", $styleDir, true);


        return true;
    }
}