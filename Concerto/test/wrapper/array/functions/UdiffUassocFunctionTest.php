<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
    StandardArrayObject,
};

class UdiffUassocFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'udiffUassoc';

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
                ["c" => "blue", "red",],
            ],
        ];
    }
}
