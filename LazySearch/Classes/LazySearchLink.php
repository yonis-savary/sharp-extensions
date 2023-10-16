<?php

namespace SharpExtensions\LazySearch\Classes;

class LazySearchLink
{
    public function __construct(
        public string $fieldLink,
        public string $fieldValue,
        public string $prefix,
        public string $suffix=""
    ){}
}