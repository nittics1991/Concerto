<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use test\Concerto\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class ChangeKeyCaseFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'changeKeyCase';

    public function executeProvider()
    {
        return [
            [
                [
                    "FirSt" => 1,
                    "SecOnd" => 4
                ],
                [CASE_UPPER],
                [
                    'FIRST' => 1,
                    'SECOND' => 4,
                ],
            ],
            [
                [
                    "FirSt" => 1,
                    "SecOnd" => 4
                ],
                [CASE_LOWER],
                [
                    'first' => 1,
                    'second' => 4,
                ],
            ],
            [
                [
                    "FirSt" => 1,
                    "SecOnd" => 4
                ],
                [],
                [
                    'first' => 1,
                    'second' => 4,
                ],
            ],
        ];
    }
}
