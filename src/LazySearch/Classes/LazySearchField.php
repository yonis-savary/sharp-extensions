<?php

namespace YonisSavary\Sharp\Extensions\LazySearch\Classes;

class LazySearchField
{
    public function __construct(
        public string $expression,
        public ?string $alias,
        public array $possibilities=[]
    ){}

    public static function fromFullExpression(string $selectExpression):self
    {
        $trimedExpression = trim($selectExpression);

        $matches = [];

        if (preg_match("/^([\"'`]).+?\\1$/", $trimedExpression))
            $trimedExpression = substr($trimedExpression, 1, strlen($trimedExpression)-2);

        if (preg_match('/(.*) as ([^ ]+)$/i', $trimedExpression, $matches))
        {
            list($_, $fieldExpression, $fieldAlias) = $matches;
        }
        else if (preg_match('/(.*) ([^ ]+)$/i', $trimedExpression, $matches))
        {
            list($_, $fieldExpression, $fieldAlias) = $matches;
        }
        else
        {
            $fieldExpression = $trimedExpression;
            $fieldAlias = preg_replace('/^.+\./', "", $trimedExpression);
        }


        if (preg_match("/^([\"'`]).+?\\1$/", $fieldAlias))
            $fieldAlias = substr($fieldAlias, 1, strlen($fieldAlias)-2);

        return new self($fieldExpression, $fieldAlias);
    }
}