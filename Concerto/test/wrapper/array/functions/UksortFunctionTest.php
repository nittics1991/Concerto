<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
    StandardArrayObject,
};

class UksortFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'uksort';

    public function executeProvider()
    {
        return [
            [
                ['a' => 4, 'b' => 8, 'c' => -1, 'd' => -9, 'e' => 2, 'f' => 5, 'g' => 3, 'h' => -4,],
                [
                    function($a, $b) {
                        if ($a == $b) {
                            return 0;
                        }
                        return ($a < $b) ? -1 : 1;
                    },
                ],
                [
                    'a' => 4,
                    'b' => 8,
                    'c' => -1,
                    'd' => -9,
                    'e' => 2,
                    'f' => 5,
                    'g' => 3,
                    'h' => -4,
                ],
            ],
        ];
    }
}
