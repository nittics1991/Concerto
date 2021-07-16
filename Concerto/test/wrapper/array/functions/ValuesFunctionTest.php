<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
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
