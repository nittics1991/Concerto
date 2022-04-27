<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use test\Concerto\wrapper\array\functions\RelatedValueTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class ShiftFunctionTest extends RelatedValueTestCase
{
    protected string $function_name = 'shift';

    public function executeProvider()
    {
        return [
            [
                ["orange", "banana", "apple", "raspberry"],
                [],
                ["banana", "apple", "raspberry",],
            ],
        ];
    }

    public function relatedValueProvider()
    {
        return [
            [
                ["orange", "banana", "apple", "raspberry"],
                [],
                "orange",
            ],
        ];
    }
}
