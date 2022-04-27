<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array;

use test\Concerto\ConcertoTestCase;
use candidate\wrapper\array\DefineMethodTrait;

class DefineMethodTraitObject1
{
    use DefineMethodTrait;

    protected $dataset;

    public function __construct($dataset)
    {
        $this->dataset = $dataset;
    }

    public function toArray()
    {
        return $this->dataset;
    }
}

class DefineMethodTraitTest extends ConcertoTestCase
{
    public function resolveDatasetProvider()
    {
        $data = range(1, 10, 1);

        return [
            //array
            [
                $data,
                $data,
            ],
            //Traversable
            [
                new \ArrayObject($data),
                $data,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider resolveDatasetProvider
    */
    public function resolveDataset(
        $dataset,
        $expect,
    ) {
      // $this->markTestIncomplete();

        $obj = new DefineMethodTraitObject1($dataset);
        $this->assertEquals(
            $expect,
            $this->callPrivateMethod(
                $obj,
                'resolveDataset',
                [$dataset]
            ),
        );
    }

    public function commomFunction1(
        $function_name,
        $src_dataset,
        $dest_dataset,
        $expect,
    ) {
        $obj = new DefineMethodTraitObject1($src_dataset);
        $actual_object = call_user_func_array(
            [$obj, $function_name],
            $dest_dataset
        );

        $this->assertEquals(
            $expect,
            $actual_object->toArray(),
        );
    }

    public function combineProvider()
    {
        $array1 = range('A', 'Z');
        $array2 = range('a', 'z');

        return [
            //basic operation with array data
            [
                'combineKeyUseKey',
                $array1,
                [$array2],
                array_combine(
                    $array2,
                    array_keys($array1),
                ),
            ],
            [
                'combineKeyUseValue',
                $array1,
                [$array2],
                array_combine(
                    $array2,
                    array_values($array1),
                ),
            ],
            [
                'combineValueUseKey',
                $array1,
                [$array2],
                array_combine(
                    array_keys($array1),
                    $array2,
                ),
            ],
            [
                'combineValueUseValue',
                $array1,
                [$array2],
                array_combine(
                    array_values($array1),
                    $array2,
                ),
            ],
            //Traversable data
            [
                'combineKeyUseKey',
                $array1,
                [new \ArrayObject($array2)],
                array_combine(
                    $array2,
                    array_keys($array1),
                ),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider combineProvider
    */
    public function combine(
        $function_name,
        $src_dataset,
        $dest_dataset,
        $expect,
    ) {
      // $this->markTestIncomplete();

        $this->commomFunction1(
            $function_name,
            $src_dataset,
            $dest_dataset,
            $expect,
        );
    }
}
