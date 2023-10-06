<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
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
