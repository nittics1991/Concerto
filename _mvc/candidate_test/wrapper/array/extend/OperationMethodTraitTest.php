<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\extend;

use test\Concerto\ConcertoTestCase;
use candidate\wrapper\array\StandardArrayObject;
use candidate\wrapper\array\extend\OperationMethodTrait;

class OperationMethodTraitObject1 extends StandardArrayObject
{
    use OperationMethodTrait;
}


class OperationMethodTraitTest extends ConcertoTestCase
{
    public function spliceAssocProvider()
    {
        $array1 = ['A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5,];
        $array2 = ['a' => 11, 'b' => 12,];

        return [
            [
                $array1,
                1,
                null,
                null,
                new OperationMethodTraitObject1(
                    ['A' => 1],
                    ['B' => 2, 'C' => 3, 'D' => 4, 'E' => 5,],
                ),
            ],
            [
                $array1,
                1,
                3,
                null,
                new OperationMethodTraitObject1(
                    ['A' => 1, 'E' => 5,],
                    ['B' => 2, 'C' => 3, 'D' => 4,],
                ),
            ],
            [
                $array1,
                1,
                3,
                $array2,
                new OperationMethodTraitObject1(
                    ['A' => 1, 'a' => 11, 'b' => 12, 'E' => 5,],
                    ['B' => 2, 'C' => 3, 'D' => 4,],
                ),
            ],
            [
                $array1,
                -1,
                null,
                null,
                new OperationMethodTraitObject1(
                    ['A' => 1, 'B' => 2, 'C' => 3, 'D' => 4,],
                    ['E' => 5,],
                ),
            ],
            [
                $array1,
                -3,
                2,
                null,
                new OperationMethodTraitObject1(
                    ['A' => 1, 'B' => 2, 'E' => 5,],
                    ['C' => 3, 'D' => 4,],
                ),
            ],
            [
                $array1,
                -3,
                2,
                $array2,
                new OperationMethodTraitObject1(
                    ['A' => 1, 'B' => 2, 'a' => 11, 'b' => 12, 'E' => 5,],
                    ['C' => 3, 'D' => 4,],
                ),
            ],
            //caution use number key
            [
                range(0, 5),
                2,
                2,
                range('A', 'B'),
                new OperationMethodTraitObject1(
                    [0 => 0, 1 => 1,],
                    [0 => 2, 1 => 3,]
                ),
            ]
        ];
    }

    /**
    *   @test
    *   @dataProvider spliceAssocProvider
    */
    public function spliceAssoc(
        $dataset,
        $position,
        $length,
        $replace_data,
        $expect,
    ) {
     // $this->markTestIncomplete();

        $obj = new OperationMethodTraitObject1($dataset);
        $this->assertEquals(
            $expect,
            $obj->spliceAssoc(
                $position,
                $length,
                $replace_data,
            ),
        );
    }

    public function insertProvider()
    {
        $array1 = range(0, 10);
        $array2 = range('A', 'Z');
        $array3 = range('a', 'z');

        $array2a = range(0, count($array2) - 1);
        $array3a = range(100, 100 +  count($array2) - 1);

        $array4 = array_combine(
            $array2,
            $array2a,
        );
        $array5 = array_combine(
            $array3,
            $array3a,
        );

        return [
            [
                $array1,
                0,
                [],
                false,
                new OperationMethodTraitObject1($array1),
            ],
            [
                $array1,
                count($array1),
                [],
                false,
                new OperationMethodTraitObject1($array1),
            ],
            [
                $array1,
                0,
                $array2,
                false,
                new OperationMethodTraitObject1(
                    array_merge($array2, $array1)
                ),
            ],
            [
                $array1,
                count($array1),
                $array2,
                false,
                new OperationMethodTraitObject1(
                    array_merge($array1, $array2),
                ),
            ],
            [
                [0,1,2,3],
                1,
                [12, 13],
                false,
                new OperationMethodTraitObject1(
                    [0, 12, 13, 1, 2, 3],
                ),
            ],
            [
                $array1,
                -1,
                $array2,
                false,
                new OperationMethodTraitObject1(
                    array_merge(
                        range(0, 9),
                        $array2,
                        [10],
                    ),
                ),
            ],
            //use preservekey
            [
                $array4,
                0,
                $array5,
                true,
                new OperationMethodTraitObject1(
                    array_merge(
                        $array5,
                        $array4,
                    ),
                ),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider insertProvider
    */
    public function insert(
        $dataset,
        $position,
        $insert_data,
        $preserve_key,
        $expect,
    ) {
     // $this->markTestIncomplete();

        $obj = new OperationMethodTraitObject1($dataset);

        $this->assertEquals(
            $expect,
            $obj->insert($position, $insert_data, $preserve_key),
        );
    }

    public function deleteProvider()
    {
        $array1 = range('A', 'E');
        $array2 = array_combine(
            $array1,
            range(0, count($array1) - 1),
        );

        return [
            [
                $array1,
                3,
                new OperationMethodTraitObject1(
                    [0 => 'A', 1 => 'B', 2 => 'C', 4 => 'E'],
                ),
            ],
            [
                $array1,
                10,
                new OperationMethodTraitObject1(
                    $array1,
                ),
            ],
            [
                $array2,
                'C',
                new OperationMethodTraitObject1(
                    ['A' => 0, 'B' => 1, 'D' => 3, 'E' => 4],
                ),
            ],
            [
                $array2,
                'z',
                new OperationMethodTraitObject1(
                    $array2,
                ),
            ],
            [
                $array2,
                ['C', 'A',],
                new OperationMethodTraitObject1(
                    ['B' => 1, 'D' => 3, 'E' => 4],
                ),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider deleteProvider
    */
    public function delete(
        $dataset,
        $key,
        $expect,
    ) {
     // $this->markTestIncomplete();

        $obj = new OperationMethodTraitObject1($dataset);

        $this->assertEquals(
            $expect,
            $obj->delete($key),
        );
    }
}
