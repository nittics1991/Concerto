<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
    StandardArrayObject,
};

class UsortFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'usort';

    public function executeProvider()
    {
        return [
            [
                [3, 2, 5, 6, 1,],
                [
                    function($a, $b) {
                        if ($a == $b) {
                            return 0;
                        }
                        return ($a < $b) ? -1 : 1;
                    },
                ],
                [
                    0 => 1,
                    1 => 2,
                    2 => 3,
                    3 => 5,
                    4 => 6,
                ],
            ],
        ];
    }
}
