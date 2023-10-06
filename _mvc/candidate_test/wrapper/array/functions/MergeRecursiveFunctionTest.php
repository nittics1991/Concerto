<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class MergeRecursiveFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'mergeRecursive';

    public function executeProvider()
    {
        return [
            [
                ["color" => ["favorite" => "red"], 5],
                [
                    [10, "color" => ["favorite" => "green", "blue"]],
                ],
                [
                    'color' => [
                        'favorite' => [0 => 'red',1 => 'green',],
                        0 => 'blue',
                    ],
                    0 => 5,
                    1 => 10,
                ],
            ],
        ];
    }
}
