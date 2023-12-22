<?php

namespace SharpExtensions\AssetsKit\Classes;

use Sharp\Classes\CLI\AbstractBuildTask;
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
    }
}