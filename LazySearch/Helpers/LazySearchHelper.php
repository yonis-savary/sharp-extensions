<?php

use Sharp\Classes\Web\Renderer;
use Sharp\Core\Utils;
use SharpExtensions\LazySearch\Classes\LazySearchLink;
use SharpExtensions\LazySearch\Classes\LazySearchOptions;
use SharpExtensions\LazySearch\Classes\LazySearch;

function lazySearch(string $url)
{
    return Renderer::getInstance()->render("LazySearch", ["url"=>$url]);
}


function lazyList(string $query, LazySearchOptions $options=null)
{
    return LazySearch::getInstance()->makeList($query, $options);
}

function lazyOptions(
    array $links=[],
    string $title=null,
    string|array $views=[],
    string|array $scripts=[],
    string|array $ignores=[],
    array $extras=[],
    array $defaultFilters=[],
    array $defaultSorts=[]
) : LazySearchOptions
{
    return new LazySearchOptions(
        $title,
        Utils::toArray($views),
        Utils::toArray($scripts),
        Utils::toArray($ignores),
        $links,
        $extras,
        $defaultFilters,
        $defaultSorts
    );
}

function lazyLink(
    string $field,
    string $prefix,
    string $value,
    string $suffix=""
) : LazySearchLink
{
    return new LazySearchLink($field, $value, $prefix, $suffix);
}