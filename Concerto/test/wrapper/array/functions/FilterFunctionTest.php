<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
    StandardArrayObject,
};

class FilterFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'filter';

    public function executeProvider()
    {
        return [
            [
                ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5],
                [
                    function($var) {
                       return $var & 1; 
                    },
                ],
                ['a'=>1,'c'=>3,'e'=>5,],
            ],
            [
                [
                    0 => 'foo',
                    1 => false,
                    2 => -1,
                    3 => null,
                    4 => '',
                    5 => '0',
                    6 => 0,
                ],
                [],
                [0=>'foo', 2 => -1],
            ],
            [
                ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4],
                [
                    function($k) {
                        return $k == 'b';
                    },
                    ARRAY_FILTER_USE_KEY,
                ],
                ['b' => 2,]
            ],
            [
                ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4],
                [
                    function($v, $k) {
                        return $k == 'b' || $v == 4;
                    },
                    ARRAY_FILTER_USE_BOTH,
                ],
                ['b' => 2, 'd'=> 4,]
            ],
        ];
    }
}
