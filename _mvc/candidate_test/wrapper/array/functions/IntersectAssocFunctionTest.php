<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
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
