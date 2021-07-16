<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
    StandardArrayObject,
};

class KeyFirstFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'keyFirst';

    public function executeProvider()
    {
        return [
            [
                ['a' => 1, 'b' => 2, 'c' => 3],
                [],
                'a',
            ],
        ];
    }
}
