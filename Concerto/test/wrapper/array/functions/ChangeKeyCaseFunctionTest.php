<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
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
