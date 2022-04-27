<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use test\Concerto\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class ValuesFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'values';

    public function executeProvider()
    {
        return [
            [
                ["size" => "XL", "color" => "gold"],
                [],
                ["XL", "gold"],
            ],
        ];
    }
}
