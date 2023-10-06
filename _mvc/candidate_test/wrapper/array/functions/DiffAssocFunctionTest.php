<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class DiffAssocFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'diffAssoc';

    public function executeProvider()
    {
        return [
            [
                ["a" => "green", "b" => "brown", "c" => "blue", "red"],
                [["a" => "green", "yellow", "red"]],
                ['b' => 'brown','c' => 'blue',0 => 'red',],
            ],
            [
                [0, 1, 2],
                [["00", "01", "2"],],
                [0 => 0,1 => 1,],
            ],
        ];
    }
}
