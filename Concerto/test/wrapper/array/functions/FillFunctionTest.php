<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
    StandardArrayObject,
};

class FillFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'fill';

    public function executeProvider()
    {
        return [
            [
                [],
                [5, 6, 'banana'],
                [
                    5=>'banana',
                    6=>'banana',
                    7=>'banana',
                    8=>'banana',
                    9=>'banana',
                    10=>'banana',
                ],
            ],
            [
                [5, 6, 'banana'],
                [-2, 4, 'pear'],
                [-2=>'pear',-1=>'pear',0=>'pear',1=>'pear',],
            ],
        ];
    }
}
