<?php

use YonisSavary\Sharp\Classes\Web\Renderer;
use YonisSavary\Sharp\Core\Utils;
use YonisSavary\Sharp\Extensions\LazySearch\Classes\LazySearchLink;
use YonisSavary\Sharp\Extensions\LazySearch\Classes\LazySearchOptions;
use YonisSavary\Sharp\Extensions\LazySearch\Classes\LazySearch;

function lazySearch(string $url, string $id=null)
{
    return (new Renderer())->render("LazySearch", ["url"=>$url, "id" => $id]);
}


function lazyList(string $query, LazySearchOptions $options=null, string $forceMode=null)
{
    return LazySearch::getInstance()->makeList($query, $options, $forceMode);
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