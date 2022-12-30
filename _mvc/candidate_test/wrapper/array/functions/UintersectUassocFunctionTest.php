<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use test\Concerto\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class UintersectUassocFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'uintersectUassoc';

    public function executeProvider()
    {
        return [
            [
                ["a" => "green", "b" => "brown", "c" => "blue", "red"],
                [
                    ["a" => "GREEN", "B" => "brown", "yellow", "red"],
                    'strcasecmp',
                    'strcasecmp',
                ],
                ["a" => "green","b" => "brown",],
            ],
        ];
    }
}