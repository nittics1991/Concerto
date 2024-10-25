<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\RelatedValueTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class SpliceFunctionTest extends RelatedValueTestCase
{
    protected string $function_name = 'splice';

    public function executeProvider()
    {
        $array1 = ["red", "green", "blue", "yellow"];
        return [
            [
                $array1,
                [2],
                ["red", "green"],
            ],
            [
                $array1,
                [1, -1],
                ["red", "yellow"],
            ],
            [
                $array1,
                [1, count($array1), "orange"],
                ["red", "orange"],
            ],
            [
                $array1,
                [-1, 1, ["black", "maroon"]],
                ["red", "green", "blue", "black", "maroon"],
            ],
        ];
    }

    public function relatedValueProvider()
    {
        $array1 = ["red", "green", "blue", "yellow"];
        return [
            [
                $array1,
                [2],
                ["blue", "yellow"],
            ],
            [
                $array1,
                [1, -1],
                ["green", "blue"],
            ],
            [
                $array1,
                [1, count($array1), "orange"],
                ["green", "blue", "yellow"],
            ],
            [
                $array1,
                [-1, 1, ["black", "maroon"]],
                ["yellow",],
            ],
        ];
    }
}
