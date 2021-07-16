<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\RelatedValueTestCase;
use Concerto\wrapper\array\{
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
