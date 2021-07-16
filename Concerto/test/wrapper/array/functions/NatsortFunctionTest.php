<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
    StandardArrayObject,
};

class NatsortFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'natsort';

    public function executeProvider()
    {
        return [
            [
                ["img12.png", "img10.png", "img2.png", "img1.png",],
                [],
                [3 => "img1.png", 2 => "img2.png", 1 => "img10.png", 0 => "img12.png",],
            ],
        ];
    }
}
