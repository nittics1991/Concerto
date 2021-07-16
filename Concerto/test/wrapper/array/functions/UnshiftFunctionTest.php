<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
    StandardArrayObject,
};

class UnshiftFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'unshift';

    public function executeProvider()
    {
        return [
            [
                ["orange", "banana"],
                [
                    "apple",
                    "raspberry"
                ],
                [
                    0=>'apple',
                    1=>'raspberry',
                    2=>'orange',
                    3=>'banana',
                ],
            ],
        ];
    }
}
