<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class RsortFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'rsort';

    public function executeProvider()
    {
        return [
            [
                ["lemon", "orange", "banana", "apple",],
                [],
                ["orange", "lemon", "banana", "apple", ],
            ],
        ];
    }
}
