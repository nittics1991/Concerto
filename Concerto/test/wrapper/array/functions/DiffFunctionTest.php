<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
    StandardArrayObject,
};

class DiffFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'diff';

    public function executeProvider()
    {
        return [
            [
                ["a" => "green", "red", "blue", "red"],
                [["b" => "green", "yellow", "red"]],
                [1=>'blue',],
            ],
        ];
    }
}
