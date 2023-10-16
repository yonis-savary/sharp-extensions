<?php

namespace Sharp\Extensions\LazySearch\Classes;

class LazySearchLink
{
    public function __construct(
        public string $fieldLink,
        public string $fieldValue,
        public string $prefix,
        public string $suffix=""
    ){}
}