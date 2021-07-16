<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
    StandardArrayObject,
};

class MergeRecursiveFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'mergeRecursive';

    public function executeProvider()
    {
        return [
            [
                ["color" => ["favorite" => "red"], 5],
                [
                    [10, "color" => ["favorite" => "green", "blue"]],
                ],
                [
                    'color'=>[
                        'favorite'=>[0=>'red',1=>'green',],
                        0=>'blue',
                    ],
                    0=>5,
                    1=>10,
                ],
            ],
        ];
    }
}
