<?php

namespace SharpExtensions\LazySearch\Classes;

use Exception;
use Sharp\Classes\Core\Component;
use Sharp\Classes\Core\Configurable;
use Sharp\Classes\Core\Logger;
use Sharp\Classes\Data\Database;
use Sharp\Classes\Data\ObjectArray;
use Sharp\Classes\Env\Cache;
use Sharp\Classes\Http\Request;
use Sharp\Classes\Http\Response;
use Sharp\Classes\Web\Renderer;
use Sharp\Core\Utils;

class LazySearch
{
    use Component, Configurable;


    /**
     * This class has three modes
     * Form : Return a webpage containing the LazySearch table
     * Data : Return JSON data to fill the LazySearch table
     * File : Stream a CSV file containing filtered data
     */
    const MODE_FORM = 'form';
    const MODE_DATA = 'data';
    const MODE_FILE = 'file';

    const DEFAULT_PARAMS = [
        'flags' => [
            "fetchQueryResultsCount" => true,
            "fetchQueryPossibilities" => true
        ],
        'mode' => 'form',
        'size' => 50,
        'page' => 0,
        'sorts' => [],
        'filters' => [],
        'search' => null
    ];

    protected $mode = self::MODE_FORM;
    protected $queryParams = [];
    protected Request $request;
    protected ?Cache $cache = null;
    protected ?LazySearchOptions $backendOptions = null;

    public static function getDefaultConfiguration(): array
    {
        return [
            'locale' => 'en',
            'ignore_links' => true,
            'template' => null,
            'size_limit' => 30,
            'export_middlewares' => [],
            'export_chunk_size' => 20_000,
            'cached' => false
        ];
    }

    public function interpretMode(?string $mode)
    {
        $this->mode = match($mode){
            'json', 'data' => self::MODE_DATA,
            'file', 'export', 'csv' => self::MODE_FILE,
            default => self::MODE_FORM
        };
    }

    public static function getDefaultInstance()
    {
        return new self(Request::buildFromGlobals());
    }

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->loadConfiguration();

        $body = $request->body() ?? [];
        if ($fileModeParams = $request->params("parameters"))
            $body = json_decode($fileModeParams, true, flags: JSON_THROW_ON_ERROR);

        $this->queryParams = array_merge(self::DEFAULT_PARAMS, $body);

        $this->interpretMode($this->queryParams["mode"] ?? "form");
    }

    protected function getCache(): Cache
    {
        $this->cache ??= Cache::getInstance()->getSubCache("LazySearch");
        return $this->cache;
    }

    protected function queryCacheKey(string $sqlQuery): string
    {
        return "query-infos-". md5($sqlQuery);
    }

    protected function possibilitiesCacheKey(string $sqlQuery): string
    {
        return "possibilities-infos-". md5($sqlQuery);
    }

    public function getClientExtras()
    {
        return $this->queryParams["extras"] ?? [];
    }

    public function getBackendOptions()
    {
        return $this->backendOptions;
    }

    public function parseQueryFields(string $query, LazySearchOptions $options): array
    {
        $infos = [];

        if ($this->isCached())
        {
            if ($cachedInfo = $this->getCache()->try($this->queryCacheKey($query)))
                return $infos = $cachedInfo;
        }

        if (!preg_match('/^SELECT ?.+FROM/is', $query))
            throw new Exception('Invalid query format !');

        $sublessQuery = preg_replace_callback(
            '/\(.+\)/',
            fn($finding) => str_repeat('#', strlen($finding[0])),
            $query
        );

        $matches = [];
        preg_match('/^SELECT ?(?:\n|.)+? ?FROM/', $sublessQuery, $matches, PREG_OFFSET_CAPTURE);

        $fullExpression = $matches[0][0];
        $infos['select-range'] = [0, strlen($fullExpression)];
        $infos['fields-range'] = [7, strlen($fullExpression)-4];
        $fullExpression = substr($fullExpression, 7, -4);

        $fields = ObjectArray::fromExplode(",", $fullExpression);

        $fields = $fields->map(LazySearchField::fromFullExpression(...))->collect();
        /** @var array<LazySearchField> $fields */

        $infos["fields"] = $fields;

        if ($this->configuration['cached'])
            $this->getCache()->set($this->queryCacheKey($query), $infos);

        return $infos;
    }

    protected function getPageExpression(): string
    {
        $size = intval($this->queryParams['size'] ?? 50);
        $size = min(200, $size);

        $page = intval($this->queryParams['page'] ?? 1);
        $offset = $size * $page;

        return " LIMIT $size OFFSET $offset";
    }

    protected function getFiltersConditions(): array
    {
        if (!($filters = $this->queryParams['filters'] ?? null))
            return [];

        $conditions = [];
        $db = Database::getInstance();

        foreach ($filters as $field => &$filterValues)
        {
            $filterValues = Utils::toArray($filterValues);

            if (!count($filterValues))
                continue;

            $forbiddenValues = new ObjectArray($filterValues);

            if ($forbiddenValues->any(is_null(...)))
                $conditions[] = $db->build("(`{}` IS NOT NULL)", [$field]);

            $conditions[] = $db->build("(`{}` NOT IN {})", [$field, $forbiddenValues->filter()->collect()]);
        }

        $this->queryParams['filters'] = $filters;
        return $conditions;
    }

    protected function getSortExpression(): string
    {
        if (!($rawSorts = $this->queryParams['sorts'] ?? null))
            return "";

        list($field, $mode) = $rawSorts;
        return Database::getInstance()->build("ORDER BY `{}` $mode", [$field]);
    }

    protected function getSearchCondition(LazySearchOptions $backendOptions, array $queryInfos): array
    {
        if (!($search = $this->queryParams['search'] ?? null))
            return [];

        $db = Database::getInstance();

        $toIgnores = $backendOptions->fieldsToIgnore;
        $displayedFields = ObjectArray::fromArray($queryInfos["fields"]);
        $displayedFields = $displayedFields->filter(fn(LazySearchField $f) => !in_array($f->alias, $toIgnores));
        $displayedFields = $displayedFields->map(fn(LazySearchField $f) => $db->build("IFNULL(`{}`, '')", [$f->alias]));

        $concatExpression = "CONCAT(". $displayedFields->join(",") . ")";

        $search = ObjectArray::fromExplode(" ", $search);
        $search = $search->map(fn($word) => $db->build("($concatExpression LIKE '%{}%')", [$word]));
        return $search->collect();
    }

    public function completeQueryWithFields(array $queryInfos, string $querySampler)
    {
        $db = Database::getInstance();

        $fieldsNames = ObjectArray::fromArray($queryInfos["fields"])
        ->map(fn(LazySearchField $f) => $db->build("`{}`", [$f->alias]))
        ->join(",");

        return "SELECT " . $fieldsNames . " " . $querySampler;
    }

    public function getQuerySampler(string $query, LazySearchOptions $backendOptions, array $queryInfos, bool $applySort=true)
    {
        $conditions = [
            ...$this->getSearchCondition($backendOptions, $queryInfos),
            ...$this->getFiltersConditions(),
        ];

        $conditions = count($conditions) ? "WHERE " . join(" AND ", $conditions): "";
        $sort = $applySort ? $this->getSortExpression() : "";

        return "FROM ($query) __ $conditions $sort";
    }

    protected function countQueryFields(string $querySampler, array &$queryInfos)
    {
        $wrappedQuery = $this->completeQueryWithFields($queryInfos, $querySampler);

        $cacheKey = $this->possibilitiesCacheKey($wrappedQuery);

        if ($this->isCached())
        {
            if ($cachedInfos = $this->getCache()->try($cacheKey))
                return $queryInfos = $cachedInfos;
        }

        $db = Database::getInstance();

        $fieldsNames =
            ObjectArray::fromArray($queryInfos["fields"])
            ->map(fn(LazySearchField $f) => $f->alias)
            ->collect();

        $fields =
            ObjectArray::fromArray($fieldsNames)
            ->map(fn($alias) =>  $db->build("COUNT(DISTINCT `{}`) as `{}`", [$alias, $alias]))
            ->join(",\n");


        $counterQuery = "SELECT $fields FROM ($wrappedQuery) as _";
        $counterResults = $db->query($counterQuery)[0];

        /** @var LazySearchField $field */
        foreach ($queryInfos["fields"] as &$field)
        {
            $alias = $field->alias;
            $count = $counterResults[$alias];

            if ($count >= $this->configuration["size_limit"])
                continue;

            $field->possibilities =
                ObjectArray::fromArray($db->query("SELECT DISTINCT `{}` FROM ($wrappedQuery) as _", [$alias]))
                ->map(fn($arr) => $arr[$alias])
                ->collect();
        }

        if ($this->isCached())
            $this->getCache()->set($cacheKey, $queryInfos);
    }

    public function makeList(string $sqlQuery, LazySearchOptions $backendOptions=null)
    {
        $backendOptions ??= new LazySearchOptions();
        $this->backendOptions = $backendOptions;

        if ($this->configuration["ignore_links"])
        {
            array_push($backendOptions->fieldsToIgnore,
                ...array_map(fn(LazySearchLink $f) => $f->fieldValue, $backendOptions->lazySearchLinks)
            );
        }

        $queryParams = &$this->queryParams;
        if (!count($queryParams['sorts']))
            $queryParams['sorts'] = $backendOptions->defaultSorts;

        if (!count($queryParams['filters']))
            $queryParams['filters'] = $backendOptions->defaultFilters;

        $queryInfos = $this->parseQueryFields($sqlQuery, $backendOptions);
        $querySampler = $this->getQuerySampler($sqlQuery, $backendOptions, $queryInfos);

        if ($this->queryParams["flags"]["fetchQueryPossibilities"] ?? true )
            $this->countQueryFields($querySampler, $queryInfos);

        switch ($this->mode)
        {
            case self::MODE_DATA: return $this->getDataResponse($querySampler, $queryInfos, $backendOptions);
            case self::MODE_FILE: return $this->getFileResponse($querySampler, $queryInfos, $backendOptions);
            case self::MODE_FORM: return $this->getViewResponse($backendOptions);
        }
    }

    public function getViewResponse(LazySearchOptions $backendOptions)
    {
        $renderer = Renderer::getInstance();

        $lazySearch = $renderer->render('LazySearch', ['url' => $this->request->getPath()]);

        if ($template = $this->configuration['template'] ?? false)
            return Response::render($template, ['lazySearch' => $lazySearch, 'lazySearchOptions' => (array)$backendOptions]);

        return Response::html($lazySearch);
    }

    public function getDataResponse(string $querySampler, array $queryInfos, LazySearchOptions $backendOptions)
    {
        $db = Database::getInstance();

        $resultCount = null;
        if ($this->queryParams["flags"]["fetchQueryResultsCount"])
            $resultCount = $db->query("SELECT COUNT(*) C $querySampler")[0]["C"];

        $wrappedQuery = $this->completeQueryWithFields($queryInfos, $querySampler) . $this->getPageExpression();

        $response = [
            'options'          => (array) $backendOptions,
            'queryParameters'  => $this->queryParams,
            'meta'             => $queryInfos,
            'resultsCount'     => $resultCount,
            'data'             => $db->query($wrappedQuery)
        ];

        return Response::json($response);
    }

    public function getFileResponse(string $querySampler, array $queryInfos, LazySearchOptions $backendOptions)
    {
        header("Content-Type: text/csv");

        $db = Database::getInstance();

        $resultCount = $db->query("SELECT COUNT(*) C $querySampler")[0]["C"];
        $wrappedQuery = $this->completeQueryWithFields($queryInfos, $querySampler);

        $stream = fopen('php://output', 'w');

        $userAgent = $this->request->getHeaders()["User-Agent"] ?? "Window";
        $utf8Of = str_contains($userAgent, "Window") ?
            fn($data) => iconv( mb_detect_encoding( $data ), 'Windows-1252//TRANSLIT', $data ):
            fn($data) => $data;

        $writeCSV = fn($data) => fputcsv($stream, $data, ";");

        if ($resultCount)
        {
            $headers = array_keys($db->query($wrappedQuery.' LIMIT 1')[0]);
            $writeCSV($headers);
            flush();

            $pageSize = $this->configuration['export_chunk_size'];
            for($offset=0; $offset<$resultCount; $offset+=$pageSize)
            {
                $chunk = $db->query($wrappedQuery . " LIMIT $pageSize OFFSET $offset");
                foreach ($chunk as $row)
                {
                    foreach ($row as &$data)
                        $data = $utf8Of($data ?? '');
                    $writeCSV($row);
                }
                flush();
            }
        }

        fclose($stream);
        die;
    }
}