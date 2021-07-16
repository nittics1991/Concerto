<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
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
