<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
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
