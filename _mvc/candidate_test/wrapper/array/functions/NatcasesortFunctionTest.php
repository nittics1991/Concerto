<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use test\Concerto\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class NatcasesortFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'natcasesort';

    public function executeProvider()
    {
        return [
            [
                ['IMG0.png', 'img12.png', 'img10.png', 'img2.png', 'img1.png', 'IMG3.png',],
                [],
                [
                    0 => 'IMG0.png',
                    4 => 'img1.png',
                    3 => 'img2.png',
                    5 => 'IMG3.png',
                    2 => 'img10.png',
                    1 => 'img12.png',
                ],
            ],
        ];
    }
}
