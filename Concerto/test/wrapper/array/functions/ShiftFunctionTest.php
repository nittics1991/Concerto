<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\RelatedValueTestCase;
use Concerto\wrapper\array\{
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
