<?php

declare(strict_types=1);

namespace test\Concerto\chart\cpchart;

use test\Concerto\ConcertoTestCase;
use Concerto\chart\cpchart\{
    ChartFactory,
    Table
};

class TableTest extends ConcertoTestCase
{
    private $tmp = __DIR__ . '\\tmp\\';
    private $data;
    private $image;

    protected function setUp(): void
    {
        $factory = new ChartFactory();
        $this->data = $factory->newData();
        $this->image = $factory->newImage(600, 400, $this->data);
    }

    public function isWithInRangeProvider()
    {
        return [
            [
                [
                    ['sx' => 10, 'ex' => 90, 'sy' => 20, 'ey' => 180, 'x' => 10, 'y' => 10, 'h' => 20],
                    100
                ],
                false,
            ],  //
            [
                [
                    ['sx' => 10, 'ex' => 90, 'sy' => 20, 'ey' => 180, 'x' => 10, 'y' => 10, 'h' => 20],
                    80
                ],
                true,
            ],  //
            [
                [
                    ['sx' => 10, 'ex' => 90, 'sy' => 20, 'ey' => 180, 'x' => 10, 'y' => 10, 'h' => 190],
                    80
                ],
                false,
            ],  //
        ];
    }

    /**
    *   @test
    *   @dataProvider isWithInRangeProvider
    */
    public function isWithInRange($data, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Table($this->data, $this->image);
        $actual = $this->callPrivateMethod($obj, 'isWithInRange', $data);
        $this->assertEquals($expect, $actual);
    }

    public function calcCellSizeProvider()
    {
        return [
            [
                [
                    'padding' => 2,
                    'font' => [
                        'FontName' => 'c:\\windows\\Fonts\\Arial.ttf',
                        'FontSize' => 10,
                    ],
                ],
                'ABCDE',
                [49, 14],
            ],  //
        ];
    }

    /**
    *   @test
    *   @dataProvider calcCellSizeProvider
    */
    public function calcCellSize($setting, $data, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Table($this->data, $this->image);

        $this->setPrivateProperty($obj, 'setting', $setting);
        $actual = $this->callPrivateMethod($obj, 'calcCellSize', [$data]);
        $this->assertEquals($expect, $actual);
    }

    public function calcDescriptionCellSizeProvider()
    {
        return [
            [
                [
                    'padding' => 2,
                    'font' => [
                        'FontName' => 'c:\\windows\\Fonts\\Arial.ttf',
                        'FontSize' => 10,
                    ],
                    'descriptions' => [
                        'abc',
                        'ABCDE',
                    ],
                ],
                [50, 14],
            ],  //
        ];
    }

    /**
    *   @test
    *   @dataProvider calcDescriptionCellSizeProvider
    */
    public function calcDescriptionCellSize($setting, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Table($this->data, $this->image);

        $this->setPrivateProperty($obj, 'setting', $setting);
        $actual = $this->callPrivateMethod($obj, 'calcDescriptionCellSize');
        $this->assertEquals($expect, $actual);
    }

    public function calcDataCellSizeProvider()
    {
        return [
            [
                [
                    'padding' => 2,
                    'font' => [
                        'FontName' => 'c:\\windows\\Fonts\\Arial.ttf',
                        'FontSize' => 10,
                    ],
                    'descriptions' => [
                        'abc',
                        'ABCDE',
                    ],
                    'dataset' => [
                        [1, 20, 100, 50, 0],
                        [2, 4, 6, 8, 10],
                    ],
                ],
                [32, 14],
            ],  //
        ];
    }

    /**
    *   @test
    *   @dataProvider calcDataCellSizeProvider
    */
    public function calcDataCellSize($setting, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Table($this->data, $this->image);

        $this->setPrivateProperty($obj, 'setting', $setting);
        $actual = $this->callPrivateMethod($obj, 'calcDataCellSize');
        $this->assertEquals($expect, $actual);
    }

    public function drawCellProvider()
    {
        return [
            [
                1,
                [
                    'padding' => 2,
                    'table' => [
                        20,
                        320,
                        580,
                        380,
                    ],
                    'font' => [
                        'FontName' => 'c:\\windows\\Fonts\\Arial.ttf',
                        'FontSize' => 10,
                    ],
                    'descriptions' => [
                        'abc',
                        'ABCDE',
                    ],
                    'dataset' => [
                        [1, 20, 100, 50, 0],
                        [2, 4, 6, 8, 10],
                    ],
                    'cell' => [
                        'R' => 0,
                        'G' => 255,
                        'B' => 0,
                        'BorderR' => 255,
                        'BorderG' => 0,
                        'BorderB' => 0,
                    ],
                ],
                [
                    'ABCDE',
                    ['sx' => 10, 'ex' => 90, 'sy' => 20, 'ey' => 180, 'x' => 10, 'y' => 10, 'h' => 12],
                    50,
                    'left',
                ],
            ],  //
            [
                2,
                [
                    'padding' => 2,
                    'table' => [
                        20,
                        320,
                        580,
                        380,
                    ],
                    'font' => [
                        'FontName' => 'c:\\windows\\Fonts\\Arial.ttf',
                        'FontSize' => 10,
                        'R' => 255,
                    ],
                    'descriptions' => [
                        'abc',
                        'ABCDE',
                    ],
                    'dataset' => [
                        [1, 20, 100, 50, 0],
                        [2, 4, 6, 8, 10],
                    ],
                    'cell' => [
                        'R' => 0,
                        'G' => 255,
                        'B' => 255,
                        'BorderR' => 0,
                        'BorderG' => 255,
                        'BorderB' => 0,
                    ],
                ],
                [
                    'ABCDE',
                    ['sx' => 10, 'ex' => 590, 'sy' => 390, 'ey' => 180, 'x' => 50, 'y' => 50, 'h' => 12],
                    100,
                    'center',
                ],
            ],  //
        ];
    }

    /**
    *   @test
    *   @dataProvider drawCellProvider
    */
    public function drawCell($i, $setting, $data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Table($this->data, $this->image);

        $this->setPrivateProperty($obj, 'setting', $setting);
        $actual = $this->callPrivateMethod($obj, 'drawCell', $data);

        $pImage = $this->getPrivateProperty($obj, 'image');
        $file = "{$this->tmp}drawCell{$i}.png";
        $pImage->render($file);

        $this->assertEquals(1, 1);
    }

    public function drawDescriptionProvider()
    {
        return [
            [
                1,
                [
                    'padding' => 4,
                    'table' => [
                        20,
                        320,
                        580,
                        380,
                    ],
                    'font' => [
                        'FontName' => 'c:\\windows\\Fonts\\Arial.ttf',
                        'FontSize' => 10,
                    ],
                    'descriptions' => [
                        'abc',
                        'ABCDE',
                        'abcde'
                    ],
                    'dataset' => [
                        [1, 20, 100, 50, 0],
                        [2, 4, 6, 8, 10],
                    ],
                    'cell' => [
                        'R' => 0,
                        'G' => 255,
                        'B' => 0,
                        'BorderR' => 255,
                        'BorderG' => 0,
                        'BorderB' => 0,
                    ],
                ],
                [
                    ['sx' => 10, 'ex' => 90, 'sy' => 20, 'ey' => 180, 'x' => 10, 'y' => 10, 'h' => 18,
                        'dw' => 60, 'rangeout' => false],
                ],
            ],  //
            [
                2,
                [
                    'padding' => 4,
                    'table' => [
                        20,
                        320,
                        580,
                        380,
                    ],
                    'font' => [
                        'FontName' => 'c:\\windows\\Fonts\\Arial.ttf',
                        'FontSize' => 10,
                    ],
                    'descriptions' => [
                        'abc',
                        'ABCDE',
                        'abcde'
                    ],
                    'dataset' => [
                        [1, 20, 100, 50, 0],
                        [2, 4, 6, 8, 10],
                    ],
                    'cell' => [
                        'R' => 0,
                        'G' => 255,
                        'B' => 0,
                        'BorderR' => 255,
                        'BorderG' => 0,
                        'BorderB' => 0,
                    ],
                ],
                [
                    ['sx' => 10, 'ex' => 40, 'sy' => 20, 'ey' => 180, 'x' => 10, 'y' => 10, 'h' => 18,
                        'dw' => 60, 'rangeout' => false],
                ],
            ],  //
        ];
    }

    /**
    *   @test
    *   @dataProvider drawDescriptionProvider
    */
    public function drawDescription($i, $setting, $data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Table($this->data, $this->image);

        $this->setPrivateProperty($obj, 'setting', $setting);
        $actual = $this->callPrivateMethod($obj, 'drawDescription', $data);

        $pImage = $this->getPrivateProperty($obj, 'image');
        $file = "{$this->tmp}drawDescription{$i}.png";
        $pImage->render($file);

        $this->assertEquals(1, 1);
    }

    public function drawDataProvider()
    {
        return [
            [
                1,
                [
                    'padding' => 4,
                    'table' => [
                        20,
                        320,
                        580,
                        380,
                    ],
                    'font' => [
                        'FontName' => 'c:\\windows\\Fonts\\Arial.ttf',
                        'FontSize' => 10,
                    ],
                    'descriptions' => [
                        'abc',
                        'ABCDE',
                        'abcde'
                    ],
                    'dataset' => [
                        [1, 20, 100, 50, 0],
                        [2, 4, 6, 8, 10],
                    ],
                    'cell' => [
                        'R' => 0,
                        'G' => 255,
                        'B' => 0,
                        'BorderR' => 255,
                        'BorderG' => 0,
                        'BorderB' => 0,
                    ],
                ],
                [
                    ['sx' => 10, 'ex' => 390, 'sy' => 20, 'ey' => 380, 'x' => 10, 'y' => 10, 'h' => 18,
                        'dw' => 60, 'rangeout' => false, 'w' => 60],
                ],
            ],  //
        ];
    }

    /**
    *   @test
    *   @dataProvider drawDataProvider
    */
    public function drawData($i, $setting, $data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Table($this->data, $this->image);

        $this->setPrivateProperty($obj, 'setting', $setting);
        $actual = $this->callPrivateMethod($obj, 'drawData', $data);

        $pImage = $this->getPrivateProperty($obj, 'image');
        $file = "{$this->tmp}drawData{$i}.png";
        $pImage->render($file);

        $this->assertEquals(1, 1);
    }

    public function renderTableProvider()
    {
        return [
            [
                1,
                []
            ],  //
            [
                2,
                [
                    'padding' => 4,
                    'table' => [
                        20,
                        300,
                        580,
                        380,
                    ],
                    'font' => [
                        'FontName' => 'c:\\windows\\Fonts\\Arial.ttf',
                        'FontSize' => 10,
                    ],
                    'descriptions' => [
                        'abc',
                        'ABCDE',
                        'abcde'
                    ],
                    'dataset' => [
                        [1, 2, 3, 4, 5],
                        [1, 20, 100, 50, 0],
                        [2, 4, 6, 8, 10],
                        [99,3333, 22,33,222],
                    ],
                    'cell' => [
                        'R' => 0,
                        'G' => 255,
                        'B' => 0,
                        'BorderR' => 255,
                        'BorderG' => 0,
                        'BorderB' => 0,
                    ],
                ],
            ],  //
        ];
    }

    /**
    *   @test
    *   @dataProvider renderTableProvider
    */
    public function renderTable($i, $data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Table($this->data, $this->image);
        $obj->render($data);

        $file = "{$this->tmp}renderTable{$i}.png";
        $this->image->render($file);

        $this->assertEquals(1, 1);
    }
}
