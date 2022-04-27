<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use test\Concerto\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class UasortFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'uasort';

    public function executeProvider()
    {
        return [
            [
                ['a' => 4, 'b' => 8, 'c' => -1, 'd' => -9, 'e' => 2, 'f' => 5, 'g' => 3, 'h' => -4,],
                [
                    function ($a, $b) {
                        if ($a == $b) {
                            return 0;
                        }
                        return ($a < $b) ? -1 : 1;
                    },
                ],
                [
                    'd' => -9,
                    'h' => -4,
                    'c' => -1,
                    'e' => 2,
                    'g' => 3,
                    'a' => 4,
                    'f' => 5,
                    'b' => 8,
                ],
            ],
        ];
    }
}
