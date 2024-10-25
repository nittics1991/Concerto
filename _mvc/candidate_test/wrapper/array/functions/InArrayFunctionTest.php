<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class InArrayFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'in_array';

    public function executeProvider()
    {
        return [
            [
                ["Mac", "NT", "Irix", "Linux"],
                ["Irix"],
                true,
            ],
            [
                ["Mac", "NT", "Irix", "Linux"],
                ["mac"],
                false,
            ],
            [
                ['1.10', 12.4, 1.13],
                ['12.4'],
                true,
            ],
            [
                ['1.10', 12.4, 1.13],
                ['12.4', true],
                false,
            ],
        ];
    }
}
