<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use test\Concerto\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class IntersectKeyFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'intersectKey';

    public function executeProvider()
    {
        return [
            [
                ['blue'  => 1, 'red'  => 2, 'green'  => 3, 'purple' => 4],
                [['green' => 5, 'blue' => 6, 'yellow' => 7, 'cyan'   => 8]],
                ['blue'  => 1, 'green'  => 3,],
            ],
        ];
    }
}
