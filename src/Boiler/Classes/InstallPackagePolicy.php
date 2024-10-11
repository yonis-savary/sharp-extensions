<?php

namespace YonisSavary\Sharp\Extensions\Boiler\Classes;

class InstallPackagePolicy
{
    public function __construct(
        public bool $allowOverwrite = false
    ){}
}