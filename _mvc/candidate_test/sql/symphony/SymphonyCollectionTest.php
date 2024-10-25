<?php

declare(strict_types=1);

namespace test\Concerto\sql\symphony;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\sql\symphony\SymphonyCollection;

class SymphonyCollectionTest extends ConcertoTestCase
{
    public $object;

    public function setUp(): void
    {
    }

    /**
    *
    */

    public static function firstProvider()
    {
        return [
            [
                [],
                [],
            ],
            [
                [
                    ['PROP_I' => 1, 'PROP_F' => 11.1, 'PROP_S' => 'AAA',],
                    ['PROP_I' => 2, 'PROP_F' => 12.1, 'PROP_S' => 'BBB',],
                    ['PROP_I' => 3, 'PROP_F' => 13.1, 'PROP_S' => 'CCC',],
                    ['PROP_I' => 4, 'PROP_F' => 14.1, 'PROP_S' => 'DDD',],
                    ['PROP_I' => 5, 'PROP_F' => 15.1, 'PROP_S' => 'EEE',],
                ],
                [
                    ['prop_i' => 1, 'prop_f' => 11.1, 'prop_s' => 'AAA',],
                    ['prop_i' => 2, 'prop_f' => 12.1, 'prop_s' => 'BBB',],
                    ['prop_i' => 3, 'prop_f' => 13.1, 'prop_s' => 'CCC',],
                    ['prop_i' => 4, 'prop_f' => 14.1, 'prop_s' => 'DDD',],
                    ['prop_i' => 5, 'prop_f' => 15.1, 'prop_s' => 'EEE',],
                ],
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('firstProvider')]
    public function toArray($data, $expect)
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new SymphonyCollection($data);
        $actual = $obj->toArray();
        $this->assertEquals($expect, $actual);
    }

    /**
    */
    #[Test]
    #[DataProvider('firstProvider')]
    public function columnNameCharCodeSJIS($data, $expect)
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $sjis_data = [];

        foreach ($data as $list) {
            $column_names = array_keys($list);
            $column_data = array_values($list);

            mb_convert_variables('SJIS', 'UTF-8', $column_names);
            mb_convert_variables('SJIS', 'UTF-8', $column_data);

            $sjis_data[] = array_combine(
                $column_names,
                $column_data,
            );
        }

        $obj = new SymphonyCollection($sjis_data);
        $actual = $obj->toArray();
        $this->assertEquals($expect, $actual);
    }

    /**
    */
    #[Test]
    #[DataProvider('firstProvider')]
    public function iteratorOperation($data, $expect)
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new SymphonyCollection($data);
        $actual = [];

        foreach ($obj as $list) {
            $actual[] = $list;
        }

        $this->assertEquals(
            $expect,
            $actual,
        );
    }
}
