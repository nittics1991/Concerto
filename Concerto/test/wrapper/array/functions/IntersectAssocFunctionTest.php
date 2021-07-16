<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
    StandardArrayObject,
};

class IntersectAssocFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'intersectAssoc';

    public function executeProvider()
    {
        return [
            [
                ["a" => "green", "b" => "brown", "c" => "blue", "red"],
                [["a" => "green", "b" => "yellow", "blue", "red"]],
                ["a" => "green",],
            ],
        ];
    }
}
