<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
    StandardArrayObject,
};

class ColumnFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'column';

    public function executeProvider()
    {
        return [
            [
                [
                    ['id' => 2135,'first_name' => 'John','last_name' => 'Doe',],
                    ['id' => 3245,'first_name' => 'Sally','last_name' => 'Smith',],
                    ['id' => 5342,'first_name' => 'Jane','last_name' => 'Jones',],
                    ['id' => 5623,'first_name' => 'Peter','last_name' => 'Doe',]
                ],
                ['first_name'],
                [0 => 'John',1 => 'Sally',2 => 'Jane',3 => 'Peter',],
            ],
            [
                [
                    ['id' => 2135,'first_name' => 'John','last_name' => 'Doe',],
                    ['id' => 3245,'first_name' => 'Sally','last_name' => 'Smith',],
                    ['id' => 5342,'first_name' => 'Jane','last_name' => 'Jones',],
                    ['id' => 5623,'first_name' => 'Peter','last_name' => 'Doe',]
                ],
                ['last_name', 'id'],
                [
                    '2135' => 'Doe',
                    '3245' => 'Smith',
                    '5342' => 'Jones',
                    '5623' => 'Doe',
                ],
            ],
        ];
    }
}
