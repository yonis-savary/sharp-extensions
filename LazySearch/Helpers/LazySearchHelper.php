<?php

use Sharp\Classes\Web\Renderer;
use Sharp\Extensions\LazySearch\Components\LazySearch;

function lazySearch(string $url)
{
    return Renderer::getInstance()->render("LazySearch", ["url"=>$url])->getContent();
}


function lazyList(
    string $query,
    array $options = [
        'links'=>[],
        'title' => 'Results',
        'views' => [],
        'scripts'=> [],
        'ignores' => [],
        'extras' => []
]) {
    return LazySearch::getInstance()->makeList($query, $options);
}

function lazyOptions(
    array $links=[],
    string $title='Results',
    string|array $views=[],
    string|array $scripts=[],
    string|array $ignores=[],
    array $extras=[],
    array $defaultFilters=[],
    array $defaultSorts=[]
) {
    return LazySearch::getInstance()->makeOptions(
        $links, $title, $views, $scripts, $ignores, $extras, $defaultFilters, $defaultSorts
    );
}

function lazyLink(
    string $field,
    string $prefix,
    string $value
) {
    return LazySearch::getInstance()->makeLink($field, $prefix, $value);
}