<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class ReduceFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'reduce';

    public function executeProvider()
    {
        return [
            [
                [1, 2, 3, 4, 5],
                [
                    function ($carry, $item) {
                        $carry += $item;
                        return $carry;
                    }
                ],
                15,
            ],
            [
                [1, 2, 3, 4, 5],
                [
                    function ($carry, $item) {
                        $carry += $item;
                        return $carry;
                    },
                    100
                ],
                115,
            ],
        ];
    }
}
