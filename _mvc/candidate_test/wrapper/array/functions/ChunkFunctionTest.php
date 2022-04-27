<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use test\Concerto\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class ChunkFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'chunk';

    public function executeProvider()
    {
        return [
            [
                ['a', 'b', 'c', 'd', 'e'],
                [2],
                [0 => [0 => 'a',1 => 'b',],1 => [0 => 'c',1 => 'd',],2 => [0 => 'e',],],
            ],
            [
                ['a', 'b', 'c', 'd', 'e'],
                [2, true],
                [0 => [0 => 'a',1 => 'b',],1 => [2 => 'c',3 => 'd',],2 => [4 => 'e',],],
            ],
        ];
    }
}
