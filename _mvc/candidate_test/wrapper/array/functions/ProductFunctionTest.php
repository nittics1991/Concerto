<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class ProductFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'product';

    public function executeProvider()
    {
        return [
            [
                [2, 4, 6, 8],
                [],
                384,
            ],
            [
                [],
                [],
                1,
            ],
        ];
    }
}
