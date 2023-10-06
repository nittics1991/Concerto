<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class DiffKeyFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'diffKey';

    public function executeProvider()
    {
        return [
            [
                ['blue'  => 1, 'red'  => 2, 'green'  => 3, 'purple' => 4],
                [['green' => 5, 'yellow' => 7, 'cyan' => 8]],
                ['blue' => 1,'red' => 2,'purple' => 4,],
            ],
            [
                ['blue' => 1, 'red'  => 2, 'green' => 3, 'purple' => 4],
                [
                    ['green' => 5, 'yellow' => 7, 'cyan' => 8],
                    ['blue' => 6, 'yellow' => 7, 'mauve' => 8],
                ],
                ['red' => 2,'purple' => 4,]
            ],
        ];
    }
}
