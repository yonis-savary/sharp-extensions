<?php

namespace SharpExtensions\Boiler\Classes;

class InstallPackagePolicy
{
    public function __construct(
        public bool $allowOverwrite = false
    ){}
}