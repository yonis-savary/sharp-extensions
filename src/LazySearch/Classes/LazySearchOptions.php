<?php

namespace YonisSavary\Sharp\Extensions\LazySearch\Classes;

class LazySearchOptions
{
    public function __construct(
        public ?string $title=null,
        public array $viewsToRender=[],
        public array $scriptToInject=[],
        public array $fieldsToIgnore=[],
        public array $lazySearchLinks=[],
        public array $extras=[],
        public array $defaultFilters=[],
        public array $defaultSorts=[]
    ){}
}