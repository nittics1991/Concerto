<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use test\Concerto\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class FillFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'fill';

    public function executeProvider()
    {
        return [
            [
                [],
                [5, 6, 'banana'],
                [
                    5 => 'banana',
                    6 => 'banana',
                    7 => 'banana',
                    8 => 'banana',
                    9 => 'banana',
                    10 => 'banana',
                ],
            ],
            [
                [5, 6, 'banana'],
                [-2, 4, 'pear'],
                [-2 => 'pear',-1 => 'pear',0 => 'pear',1 => 'pear',],
            ],
        ];
    }
}
