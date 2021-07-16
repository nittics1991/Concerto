<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
    StandardArrayObject,
};

class SearchFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'search';

    public function executeProvider()
    {
        return [
            [
                [0 => 'blue', 1 => 'red', 2 => 'green', 3 => 'red'],
                ['green'],
                2,
            ],
            [
                ['0' => 'blue', '1' => 'red', '2' => 'green', '3' => 'red'],
                ['green'],
                '2',
            ],
            [
                range(1, 10, 1),
                ['2'],
                1,
            ],
            [
                range(1, 10, 1),
                ['2', true],
                false,
            ],
        ];
    }
}
