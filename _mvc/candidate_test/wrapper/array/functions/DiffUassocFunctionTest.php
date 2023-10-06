<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class DiffUassocFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'diffUassoc';

    public function executeProvider()
    {
        return [
            [
                ["a" => "green", "b" => "brown", "c" => "blue", "red"],
                [
                    ["a" => "green", "yellow", "red"],
                    function ($a, $b) {
                        if ($a === $b) {
                            return 0;
                        }
                        return ($a > $b) ? 1 : -1;
                    }
                ],
                ['b' => 'brown','c' => 'blue',0 => 'red',],
            ],
        ];
    }
}
