<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use test\Concerto\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class KeyExistsFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'keyExists';

    public function executeProvider()
    {
        return [
            [
                ['first' => 1, 'second' => 4],
                ['first'],
                true,
            ],
        ];
    }
}
