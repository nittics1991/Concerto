<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class IntersectUassocFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'intersectUassoc';

    public function executeProvider()
    {
        return [
            [
                ["a" => "green", "b" => "brown", "c" => "blue", "red"],
                [
                    ["a" => "GREEN", "B" => "brown", "yellow", "red"],
                    'strcasecmp',
                ],
                ['b' => 'brown',],
            ],
        ];
    }
}
