<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
    StandardArrayObject,
};

class ReplaceRecursiveFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'replaceRecursive';

    public function executeProvider()
    {
        return [
            [
                [
                    'citrus' => ["orange"],
                    'berries' =>["blackberry", "raspberry"],
                ],
                [
                    [
                        'citrus' => ['pineapple'],
                        'berries' => ['blueberry'],
                    ],
                ],
                [
                    'citrus'=>[0=>'pineapple',],
                    'berries'=>[0=>'blueberry',1=>'raspberry',],
                ],
            ],
            [
                [
                    'citrus'=>[0=>'orange',],
                    'berries'=>[0=>'blackberry',1=>'raspberry',],
                    'others'=>'banana',
                ],
                [
                    [
                        'citrus'=>'pineapple',
                        'berries'=>[0=>'blueberry',],
                        'others'=>[0=>'litchis',],
                    ],
                    [
                        'citrus'=>[0=>'pineapple',],
                        'berries'=>[0=>'blueberry',],
                        'others'=>'litchis',
                    ],
                ],
                [
                    'citrus'=>[0=>'pineapple',],
                    'berries'=>[0=>'blueberry',1=>'raspberry',],
                    'others'=>'litchis',
                ],
            ],
        ];
    }
}
