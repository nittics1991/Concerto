<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class KeyLastFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'keyLast';

    public function executeProvider()
    {
        return [
            [
                ['a' => 1, 'b' => 2, 'c' => 3],
                [],
                'c',
            ],
            [
                [],
                [],
                null,
            ],
        ];
    }
}
