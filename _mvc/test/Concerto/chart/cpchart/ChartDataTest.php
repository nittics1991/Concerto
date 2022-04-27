<?php

declare(strict_types=1);

namespace test\Concerto\chart\cpchart;

use test\Concerto\ConcertoTestCase;
use Concerto\chart\cpchart\ChartData;

class ChartDataTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function get1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $data = [
            'data1' => 1,
            'array1' => [
                'data11' => 11,
                'array12' => [
                    'data121' => 121,
                    'data122' => 122,
                ],
                'data13' => 13,
            ],
        ];
        $obj = new ChartData($data);
        $this->assertEquals($data, $obj->get());
        $this->assertEquals($data['array1'], $obj->get('array1'));

        $obj = new ChartData();
        $obj = $obj->bind($data);
        $this->assertEquals($data, $obj->get());

        $data2 = [
            'array1' => [
                'data11' => 'A',
                'array12' => [
                    'other' => 'B',
                    'data121' => 'C',
                ],
                'data13' => 'D',
            ],
            'other' => 'E',
        ];

        $expect = [
            'data1' => 1,
            'array1' => [
                'data11' => 'A',
                'array12' => [
                    'data121' => 'C',
                    'data122' => 122,
                    'other' => 'B',
                ],
                'data13' => 'D',
            ],
            'other' => 'E',
        ];
        $obj2 = $obj->bind($data2);
        $this->assertEquals($expect, $obj2->get());

        $ar2 = $obj2->get();
        $this->assertEquals(
            $data2['array1']['array12']['data121'],
            $ar2['array1']['array12']['data121']
        );

        $count = 0;
        foreach ($obj2 as $val) {
            $count++;
        }
        $this->assertEquals(9, $count);
    }

    /**
    *   @test
    */
    public function testGetInfoException()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $file = __DIR__ . '\\tmp\\dummy.conf';
        $obj = new ChartData();
        $obj = $obj->import($file);
    }

    /**
    *   @test
    */
    public function import1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = __DIR__ . '\\tmp\\config1.conf';

        $expect = [
            'data1' => 1,
            'array1' => [
                'data11' => 'A',
                'array12' => [
                    'data121' => 'C',
                    'data122' => 122,
                    'other' => 'B',
                ],
                'data13' => 'D',
            ],
            'other' => 'E',
        ];
        $obj = new ChartData();
        $obj = $obj->import($file);
        $this->assertEquals($expect, $obj->get());
    }

    public function getTableDataProvider()
    {
        return [
            [
                [
                    'points' => [
                        'chart1' => [1, 2, 3, 4, 5],
                        'chart2' => [11, 12, 13, 14, 15],
                        'chart3' => [15, 12, 14, 13, 11],
                    ],
                    'dataset' => [
                        'Abscissa' => [
                            'chart2',
                        ],
                    ],
                ],
                [
                        ['', 11, 12, 13, 14, 15],
                        ['chart1', 1, 2, 3, 4, 5],
                        ['chart3', 15, 12, 14, 13, 11],
                ],
            ],// not have descriptions

            [
                [
                    'points' => [
                        'chart1' => [1, 2, 3, 4, 5],
                        'chart2' => [11, 12, 13, 14, 15],
                        'chart3' => [15, 12, 14, 13, 11],
                    ],
                    'dataset' => [
                    ],
                ],
                [
                        ['chart1', 1, 2, 3, 4, 5],
                        ['chart2', 11, 12, 13, 14, 15],
                        ['chart3', 15, 12, 14, 13, 11],
                ],
            ],// not have abscissa

            [
                [
                    'points' => [
                        'chart1' => [1, 2, 3, 4, 5],
                        'chart2' => [11, 12, 13, 14, 15],
                        'chart3' => [15, 12, 14, 13, 11],
                    ],
                    'dataset' => [
                        'Abscissa' => [
                            'chart2',
                        ],
                        'SerieDescription' => [
                            ['chart1', '温度'],
                            ['chart2', '日付'],
                            ['chart3', '湿度'],
                        ],
                    ],
                ],
                [
                        ['', 11, 12, 13, 14, 15],
                        ['温度', 1, 2, 3, 4, 5],
                        ['湿度', 15, 12, 14, 13, 11],
                ],
            ],// count(SerieDescription) == count(points)

            [
                [
                    'points' => [
                        'chart1' => [1, 2, 3, 4, 5],
                        'chart2' => [11, 12, 13, 14, 15],
                        'chart3' => [15, 12, 14, 13, 11],
                    ],
                    'dataset' => [
                        'Abscissa' => [
                            'chart2',
                        ],
                        'SerieDescription' => [
                            ['chart1', '温度'],
                        ],
                    ],
                ],
                [
                        ['', 11, 12, 13, 14, 15],
                        ['温度', 1, 2, 3, 4, 5],
                        ['chart3', 15, 12, 14, 13, 11],
                ],
            ],// count(SerieDescription) != count(points)
        ];
    }

    /**
    *   @test
    *   @dataProvider getTableDataProvider
    */
    public function getTableData($data, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new ChartData();
        $obj = $obj->bind($data);
        $this->assertEquals($expect, $obj->getTableData());
    }
}
