<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use test\Concerto\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class IntersectUkeyFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'intersectUkey';

    public function executeProvider()
    {
        return [
            [
                ['blue'  => 1, 'red'  => 2, 'green'  => 3, 'purple' => 4],
                [
                    ['green' => 5, 'blue' => 6, 'yellow' => 7, 'cyan'   => 8],
                    function ($key1, $key2) {
                        if ($key1 == $key2) {
                            return 0;
                        } elseif ($key1 > $key2) {
                            return 1;
                        } else {
                            return -1;
                        }
                    },
                ],
                ['blue'  => 1, 'green'  => 3],
            ],
        ];
    }
}
