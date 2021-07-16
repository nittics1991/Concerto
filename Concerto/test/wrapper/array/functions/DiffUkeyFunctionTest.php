<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
    StandardArrayObject,
};

class DiffUkeyFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'diffUkey';

    public function executeProvider()
    {
        return [
            [
                ['blue'  => 1, 'red'  => 2, 'green'  => 3, 'purple' => 4],
                [
                    ['green' => 5, 'blue' => 6, 'yellow' => 7, 'cyan'   => 8],
                    function($key1, $key2) {
                        if ($key1 == $key2)
                            return 0;
                        else if ($key1 > $key2)
                            return 1;
                        else
                            return -1;
                    },
                ],
                ['red'=>2,'purple'=>4,],
            ],
        ];
    }
}
