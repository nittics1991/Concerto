<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class FillKeysFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'fillKeys';

    public function executeProvider()
    {
        return [
            [
                ['foo', 5, 10, 'bar'],
                [
                    'banana'
                ],
                [
                    'foo' => 'banana',
                    5 => 'banana',
                    10 => 'banana',
                    'bar' => 'banana',
                ],
            ],
        ];
    }
}
