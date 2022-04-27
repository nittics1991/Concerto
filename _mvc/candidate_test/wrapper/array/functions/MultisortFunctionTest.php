<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use test\Concerto\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class MultisortFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'multisort';

    public function executeProvider()
    {
        return [
            [
                [10, 100, 100, 0],
                [
                    [1, 3, 2, 4],
                ],
                [0 => 0,1 => 10,2 => 100,3 => 100,],
            ],
        ];
    }
}
