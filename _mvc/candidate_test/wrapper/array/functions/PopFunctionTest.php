<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\RelatedValueTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class PopFunctionTest extends RelatedValueTestCase
{
    protected string $function_name = 'pop';

    public function executeProvider()
    {
        return [
            [
                ["orange", "banana", "apple", "raspberry"],
                [],
                ["orange", "banana", "apple",],
            ],
        ];
    }

    public function relatedValueProvider()
    {
        return [
            [
                ["orange", "banana", "apple", "raspberry"],
                [],
                "raspberry",
            ],
        ];
    }
}
