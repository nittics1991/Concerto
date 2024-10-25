<?php

declare(strict_types=1);

namespace test\Concerto\chart\cpchart;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\chart\cpchart\CpChartBuilder;

class CpChartBuilderTest extends ConcertoTestCase
{
    private $tmp = __DIR__ . '\\tmp\\';

    public static function setPointsProvider()
    {
        return [
            [
                [
                    'points' => [
                        'chart1' => [1, 2, 3, 4, 5],
                        'chart2' => [11, 12, 13, 14, 15],
                    ],
                ],
            ],  //
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('setPointsProvider')]
    public function setPoints($data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new CpChartBuilder();

        $this->callPrivateMethod($obj, 'addPoints', [$data]);
        $pData = $this->getPrivateProperty($obj, 'data');
        $dataset = $pData->getData();
        $count = 0;

        foreach ($data['points'] as $key => $val) {
            $this->assertEquals($val, $dataset['Series'][$key]['Data']);
            $count++;
        }
        $this->assertEquals(count($data['points']), $count);
    }

    public static function rgbaToAarrayProvider()
    {
        return [
            [
                [
                    '#aa1299ff',
                    []
                ],
                [170, 18, 153, 100],
            ],  //
            [
                [
                    '#aa1299fe',
                    ['R', 'G', 'B', 'Alpha']
                ],
                ['R' => 170, 'G' => 18, 'B' => 153, 'Alpha' => 99],
            ],  //
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('rgbaToAarrayProvider')]
    public function rgbaToAarray($data, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new CpChartBuilder();

        $actual = $this->callPrivateMethod($obj, 'rgbaToAarray', $data);

        $this->assertEquals($expect, $actual);
    }

    public static function drawCanvasProvider()
    {
        return [
            [
                1,
                [
                    'canvas' => [
                        'width' => 700,
                        'height' => 300,
                        'rgba' => '#aa1299ff',
                    ],
                ],
            ],  //
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('drawCanvasProvider')]
    public function drawCanvas($i, $data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new CpChartBuilder();

        $this->callPrivateMethod($obj, 'drawCanvas', [$data]);

        $pImage = $this->getPrivateProperty($obj, 'image');
        $file = "{$this->tmp}canvas{$i}.png";
        $pImage->render($file);

        $this->assertEquals(1, 1);
    }

    public static function afterCanvasProvider()
    {
        return [
            [
                1,
                [
                    'points' => [
                        'chart1' => [1, 2, 3, 4, 5],
                        'chart2' => [11, 12, 13, 14, 15],
                    ],
                    'canvas' => [
                        'width' => 700,
                        'height' => 300,
                        'rgba' => '#ffeeddff',
                    ],
                    'afterCanvas' => function ($factory, $data, $image, $builder) {
                        $image->drawText(10, 20, 'afterCanvas');
                    }
                ],
            ],  //
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('afterCanvasProvider')]
    public function afterCanvas($i, $data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new CpChartBuilder();

        $this->callPrivateMethod($obj, 'addPoints', [$data]);
        $this->callPrivateMethod($obj, 'drawCanvas', [$data]);
        $this->callPrivateMethod($obj, 'afterCanvas', [$data]);

        $pImage = $this->getPrivateProperty($obj, 'image');
        $file = "{$this->tmp}afterCanvas{$i}.png";
        $pImage->render($file);

        $this->assertEquals(1, 1);
    }

    public static function drawChartAreaProvider()
    {
        return [
            [
                1,
                [
                    'points' => [
                        'chart1' => [1, 2, 3, 4, 5],
                        'chart2' => [11, 12, 13, 14, 15],
                    ],
                    'canvas' => [
                        'width' => 700,
                        'height' => 500,
                        'rgba' => '#ffeeddff',
                    ],
                    'chartArea' => [
                        'marginTop' => 10,
                        'marginBottom' => 20,
                        'marginLeft' => 30,
                        'marginRight' => 40,
                        'rgba' => '#aa1299ff'
                    ],
                ],
            ],  //
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('drawChartAreaProvider')]
    public function drawChartArea($i, $data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new CpChartBuilder();

        $this->callPrivateMethod($obj, 'addPoints', [$data]);
        $this->callPrivateMethod($obj, 'drawCanvas', [$data]);
        $this->callPrivateMethod($obj, 'drawChartArea', [$data]);

        $pImage = $this->getPrivateProperty($obj, 'image');
        $file = "{$this->tmp}chartArea{$i}.png";
        $pImage->render($file);

        $this->assertEquals(1, 1);
    }

    public static function drawAfterChartAreaProvider()
    {
        return [
            [
                1,
                [
                    'points' => [
                        'chart1' => [1, 2, 3, 4, 5],
                        'chart2' => [11, 12, 13, 14, 15],
                    ],
                    'canvas' => [
                        'width' => 700,
                        'height' => 500,
                        'rgba' => '#ffeeddff',
                    ],
                    'chartArea' => [
                        'marginTop' => 10,
                        'marginBottom' => 20,
                        'marginLeft' => 30,
                        'marginRight' => 40,
                        'rgba' => '#ddffeeff'
                    ],
                    'afterChartArea' => function ($factory, $data, $image, $builder) {
                        $image->drawText(10, 20, 'afterChartArea');
                    }
                ],
            ],  //
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('drawAfterChartAreaProvider')]
    public function drawAfterChartArea($i, $data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new CpChartBuilder();

        $this->callPrivateMethod($obj, 'addPoints', [$data]);
        $this->callPrivateMethod($obj, 'drawCanvas', [$data]);
        $this->callPrivateMethod($obj, 'drawChartArea', [$data]);
        $this->callPrivateMethod($obj, 'afterChartArea', [$data]);

        $pImage = $this->getPrivateProperty($obj, 'image');
        $file = "{$this->tmp}afterChartArea{$i}.png";
        $pImage->render($file);

        $this->assertEquals(1, 1);
    }

    public static function setDatasetProvider()
    {
        return [
            [
                1,
                [
                    'points' => [
                        'chart1' => [1, 2, 3, 4, 5],
                        'chart2' => [11, 12, 13, 14, 15],
                        'chart3' => [15, 12, 14, 13, 11],
                    ],
                    'canvas' => [
                        'width' => 700,
                        'height' => 500,
                        'rgba' => '#ffeeddff',
                    ],
                    'chartArea' => [
                        'marginTop' => 100,
                        'marginBottom' => 100,
                        'marginLeft' => 100,
                        'marginRight' => 100,
                        'rgba' => '#ddffeeff'
                    ],
                    'dataset' => [
                        'Abscissa' => [
                            'chart1',
                        ],
                        'AbscissaName' => [
                            'TEMPARATURE',
                        ],
                        'SerieOnAxis' => [
                            ['chart2', 0],
                            ['chart3', 1],
                        ],
                        'AxisName' => [
                            [0, 'TEMP'],
                            [1, 'HUMIDITY'],
                        ],
                    ],
                ],
            ],  //
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('setDatasetProvider')]
    public function setDataset($i, $data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new CpChartBuilder();

        $this->callPrivateMethod($obj, 'addPoints', [$data]);
        $this->callPrivateMethod($obj, 'drawCanvas', [$data]);
        $this->callPrivateMethod($obj, 'drawChartArea', [$data]);
        $this->callPrivateMethod($obj, 'setDataset', [$data]);

        $pImage = $this->getPrivateProperty($obj, 'image');
        $pImage->drawScale();

        $file = "{$this->tmp}setDataset{$i}.png";
        $pImage->render($file);

        $this->assertEquals(1, 1);
    }

    public static function drawScaleProvider()
    {
        return [
            [
                1,
                [
                    'points' => [
                        'chart1' => [1, 2, 3, 4, 5],
                        'chart2' => [11, 12, 13, 14, 15],
                        'chart3' => [15, 12, 14, 13, 11],
                    ],
                    'canvas' => [
                        'width' => 700,
                        'height' => 500,
                        'rgba' => '#ffeeddff',
                    ],
                    'chartArea' => [
                        'marginTop' => 100,
                        'marginBottom' => 100,
                        'marginLeft' => 100,
                        'marginRight' => 100,
                        'rgba' => '#ddffeeff'
                    ],
                    'dataset' => [
                        'Abscissa' => [
                            'chart1',
                        ],
                        'AbscissaName' => [
                            'TEMPARATURE',
                        ],
                        'SerieOnAxis' => [
                            ['chart2', 0],
                            ['chart3', 1],
                        ],
                        'AxisName' => [
                            [0, 'TEMP'],
                            [1, 'HUMIDITY'],
                        ],
                    ],
                    'scale' => [
                        'font' => [
                            'R' => 255,
                        ],
                        'format' => [
                            'Pos' => SCALE_POS_TOPBOTTOM,
                            'DrawArrows' => true,
                        ],
                    ],
                ],
            ],  //
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('drawScaleProvider')]
    public function drawScale($i, $data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new CpChartBuilder();

        $this->callPrivateMethod($obj, 'addPoints', [$data]);
        $this->callPrivateMethod($obj, 'drawCanvas', [$data]);
        $this->callPrivateMethod($obj, 'drawChartArea', [$data]);
        $this->callPrivateMethod($obj, 'setDataset', [$data]);
        $this->callPrivateMethod($obj, 'drawScale', [$data]);

        $pImage = $this->getPrivateProperty($obj, 'image');
        $file = "{$this->tmp}drawScale{$i}.png";
        $pImage->render($file);

        $this->assertEquals(1, 1);
    }

    public static function afterScaleProvider()
    {
        return [
            [
                1,
                [
                    'points' => [
                        'chart1' => [1, 2, 3, 4, 5],
                        'chart2' => [11, 12, 13, 14, 15],
                        'chart3' => [15, 12, 14, 13, 11],
                    ],
                    'canvas' => [
                        'width' => 700,
                        'height' => 500,
                        'rgba' => '#ffeeddff',
                    ],
                    'chartArea' => [
                        'marginTop' => 100,
                        'marginBottom' => 100,
                        'marginLeft' => 100,
                        'marginRight' => 100,
                        'rgba' => '#ddffeeff'
                    ],
                    'dataset' => [
                        'Abscissa' => [
                            'chart1',
                        ],
                        'AbscissaName' => [
                            'TEMPARATURE',
                        ],
                        'SerieOnAxis' => [
                            ['chart2', 0],
                            ['chart3', 1],
                        ],
                        'AxisName' => [
                            [0, 'TEMP'],
                            [1, 'HUMIDITY'],
                        ],
                    ],
                    'scale' => [
                        'font' => [
                            'R' => 255,
                        ],
                        'format' => [
                            'Pos' => SCALE_POS_TOPBOTTOM,
                            'DrawArrows' => true,
                        ],
                    ],
                    'afterScale' => function ($factory, $data, $image, $builder) {
                        $image->drawThreshold(12.5, ['R' => 255]);
                    }
                ],
            ],  //
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('afterScaleProvider')]
    public function afterScale($i, $data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new CpChartBuilder();

        $this->callPrivateMethod($obj, 'addPoints', [$data]);
        $this->callPrivateMethod($obj, 'drawCanvas', [$data]);
        $this->callPrivateMethod($obj, 'drawChartArea', [$data]);
        $this->callPrivateMethod($obj, 'setDataset', [$data]);
        $this->callPrivateMethod($obj, 'drawScale', [$data]);
        $this->callPrivateMethod($obj, 'afterScale', [$data]);

        $pImage = $this->getPrivateProperty($obj, 'image');
        $file = "{$this->tmp}afterScale{$i}.png";
        $pImage->render($file);

        $this->assertEquals(1, 1);
    }

    public static function setPaletteProvider()
    {
        return [
            [
                1,
                [
                    'points' => [
                        'chart1' => [1, 2, 3, 4, 5],
                        'chart2' => [11, 12, 13, 14, 15],
                        'chart3' => [15, 12, 14, 13, 11],
                    ],
                    'canvas' => [
                        'width' => 700,
                        'height' => 500,
                        'rgba' => '#ffeeddff',
                    ],
                    'chartArea' => [
                        'marginTop' => 100,
                        'marginBottom' => 100,
                        'marginLeft' => 100,
                        'marginRight' => 100,
                        'rgba' => '#ddffeeff'
                    ],
                    'dataset' => [
                        'Abscissa' => [
                            'chart1',
                        ],
                        'AbscissaName' => [
                            'TEMPARATURE',
                        ],
                        'SerieOnAxis' => [
                            ['chart2', 0],
                            ['chart3', 1],
                        ],
                        'AxisName' => [
                            [0, 'TEMP'],
                            [1, 'HUMIDITY'],
                        ],
                    ],
                    'scale' => [
                        'font' => [
                            'R' => 255,
                        ],
                        'format' => [
                            'Pos' => SCALE_POS_TOPBOTTOM,
                            'DrawArrows' => true,
                        ],
                    ],
                    'palette' => __DIR__ . '\\tmp\\test.color',
                ],
                [
                    ['R' => '255', 'G' => '0', 'B' => '0', 'Alpha' => "100"],
                    ['R' => '0', 'G' => '255', 'B' => '0', 'Alpha' => "0"],
                    ['R' => 0, 'G' => 0, 'B' => 0, 'Alpha' => 0],
                ],
            ],  //
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('setPaletteProvider')]
    public function setPalette($i, $data, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new CpChartBuilder();

        $this->callPrivateMethod($obj, 'addPoints', [$data]);
        $this->callPrivateMethod($obj, 'drawCanvas', [$data]);
        $this->callPrivateMethod($obj, 'drawChartArea', [$data]);
        $this->callPrivateMethod($obj, 'setDataset', [$data]);
        $this->callPrivateMethod($obj, 'drawScale', [$data]);
        $this->callPrivateMethod($obj, 'setPalette', [$data]);

        $pData = $this->getPrivateProperty($obj, 'data');
        $data = $pData->getData();
        $i = 0;

        foreach ($data['Series'] as $name => $chart) {
            $this->assertEquals($expect[$i], $data['Series'][$name]['Color']);
            $i++;
        }
    }

    public static function drawChartsProvider()
    {
        return [
            [
                1,
                [
                    'points' => [
                        'chart1' => [1, 2, 3, 4, 5],
                        'chart2' => [11, 12, 13, 14, 15],
                        'chart3' => [15, 12, 14, 13, 11],
                    ],
                    'canvas' => [
                        'width' => 700,
                        'height' => 500,
                        'rgba' => '#ffeeddff',
                    ],
                    'chartArea' => [
                        'marginTop' => 100,
                        'marginBottom' => 100,
                        'marginLeft' => 100,
                        'marginRight' => 100,
                        'rgba' => '#ddffeeff'
                    ],
                    'dataset' => [
                        'Abscissa' => [
                            'chart1',
                        ],
                        'AbscissaName' => [
                            'TEMPARATURE',
                        ],
                        'SerieOnAxis' => [
                            ['chart2', 0],
                            ['chart3', 1],
                        ],
                        'AxisName' => [
                            [0, 'TEMP'],
                            [1, 'HUMIDITY'],
                        ],
                    ],
                    'scale' => [
                        'font' => [
                            'R' => 255,
                        ],
                        'format' => [
                            'Pos' => SCALE_POS_TOPBOTTOM,
                            'DrawArrows' => true,
                        ],
                    ],
                    'charts'  => [
                        [
                            'drawable' => ['chart2', 'chart3'],
                            'type' => 'LineChart',
                            'format' => [
                                'DisplayValues' => true,
                                'DisplayG' => 255,
                            ],
                            'font' => [
                                'FontSize' => 20,
                            ],
                        ],
                        [
                            'drawable' => ['chart3'],
                            'type' => 'PlotChart',
                            'format' => [
                                'PlotSize' => 8,
                            ],
                        ],
                    ],
                ],
            ],  //
            [
                2,
                [
                    'points' => [
                        'chart1' => [1, 2, 3, 4, 5],
                        'chart2' => [11, 12, 13, 14, 15],
                        'chart3' => [15, 12, 14, 13, 11],
                    ],
                    'canvas' => [
                        'width' => 700,
                        'height' => 500,
                        'rgba' => '#ffeeddff',
                    ],
                    'chartArea' => [
                        'marginTop' => 100,
                        'marginBottom' => 100,
                        'marginLeft' => 100,
                        'marginRight' => 100,
                        'rgba' => '#ddffeeff'
                    ],
                    'dataset' => [
                        'Abscissa' => ['chart1'],
                    ],
                    'charts'  => [
                        [
                            'drawable' => ['chart3'],
                            'type' => '2DPie',
                            'class' => 'pie',
                            'options' => [
                                300,
                                200,
                                [
                                    'Radius' => 100,
                                    'DrawLabels' => true,
                                ],
                            ],
                            'font' => [
                                'FontSize' => 20,
                            ],
                        ],
                    ],
                ],
            ],  //
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('drawChartsProvider')]
    public function drawCharts($i, $data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new CpChartBuilder();

        $this->callPrivateMethod($obj, 'addPoints', [$data]);
        $this->callPrivateMethod($obj, 'drawCanvas', [$data]);
        $this->callPrivateMethod($obj, 'drawChartArea', [$data]);
        $this->callPrivateMethod($obj, 'setDataset', [$data]);

        if (isset($data['scale'])) {
            $this->callPrivateMethod($obj, 'drawScale', [$data]);
        }
        $this->callPrivateMethod($obj, 'drawCharts', [$data]);

        $pImage = $this->getPrivateProperty($obj, 'image');
        $file = "{$this->tmp}drawCharts{$i}.png";
        $pImage->render($file);

        $this->assertEquals(1, 1);
    }

    public static function afterChartsProvider()
    {
        return [
            [
                1,
                [
                    'points' => [
                        'chart1' => [1, 2, 3, 4, 5],
                        'chart2' => [11, 12, 13, 14, 15],
                        'chart3' => [15, 12, 14, 13, 11],
                    ],
                    'canvas' => [
                        'width' => 700,
                        'height' => 500,
                        'rgba' => '#ffeeddff',
                    ],
                    'chartArea' => [
                        'marginTop' => 100,
                        'marginBottom' => 100,
                        'marginLeft' => 100,
                        'marginRight' => 100,
                        'rgba' => '#ddffeeff'
                    ],
                    'dataset' => [
                        'Abscissa' => [
                            'chart1',
                        ],
                        'AbscissaName' => [
                            'TEMPARATURE',
                        ],
                        'SerieOnAxis' => [
                            ['chart2', 0],
                            ['chart3', 1],
                        ],
                        'AxisName' => [
                            [0, 'TEMP'],
                            [1, 'HUMIDITY'],
                        ],
                    ],
                    'scale' => [
                        'font' => [
                            'R' => 255,
                        ],
                        'format' => [
                            'Pos' => SCALE_POS_TOPBOTTOM,
                            'DrawArrows' => true,
                        ],
                    ],
                    'charts'  => [
                        [
                            'drawable' => ['chart2', 'chart3'],
                            'type' => 'LineChart',
                            'format' => [
                                'DisplayValues' => true,
                                'DisplayG' => 255,
                            ],
                            'font' => [
                                'FontSize' => 20,
                            ],
                        ],
                        [
                            'drawable' => ['chart3'],
                            'type' => 'PlotChart',
                            'format' => [
                                'PlotSize' => 8,
                            ],
                        ],
                    ],
                    'afterCharts' => function ($factory, $data, $image, $builder) {
                        $image->drawThreshold(12.5, ['R' => 255]);
                    }
                ],
            ],  //
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('afterChartsProvider')]
    public function afterCharts($i, $data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new CpChartBuilder();

        $this->callPrivateMethod($obj, 'addPoints', [$data]);
        $this->callPrivateMethod($obj, 'drawCanvas', [$data]);
        $this->callPrivateMethod($obj, 'drawChartArea', [$data]);
        $this->callPrivateMethod($obj, 'setDataset', [$data]);
        $this->callPrivateMethod($obj, 'drawScale', [$data]);
        $this->callPrivateMethod($obj, 'drawCharts', [$data]);
        $this->callPrivateMethod($obj, 'afterCharts', [$data]);

        $pImage = $this->getPrivateProperty($obj, 'image');
        $file = "{$this->tmp}afterCharts{$i}.png";
        $pImage->render($file);

        $this->assertEquals(1, 1);
    }

    public static function drawLegendProvider()
    {
        return [
            [
                1,
                [
                    'points' => [
                        'chart1' => [1, 2, 3, 4, 5],
                        'chart2' => [11, 12, 13, 14, 15],
                        'chart3' => [15, 12, 14, 13, 11],
                    ],
                    'canvas' => [
                        'width' => 700,
                        'height' => 500,
                        'rgba' => '#ffeeddff',
                    ],
                    'chartArea' => [
                        'marginTop' => 100,
                        'marginBottom' => 100,
                        'marginLeft' => 100,
                        'marginRight' => 100,
                        'rgba' => '#ddffeeff'
                    ],
                    'dataset' => [
                        'Abscissa' => [
                            'chart1',
                        ],
                        'AbscissaName' => [
                            'TEMPARATURE',
                        ],
                        'SerieOnAxis' => [
                            ['chart2', 0],
                            ['chart3', 1],
                        ],
                        'AxisName' => [
                            [0, 'TEMP'],
                            [1, 'HUMIDITY'],
                        ],
                        'SerieDescription' => [
                            ['chart2', 'CHART2'],
                            ['chart3', 'CHART3'],
                        ],
                    ],
                    'scale' => [
                        'font' => [
                            'R' => 255,
                        ],
                        'format' => [
                            'Pos' => SCALE_POS_TOPBOTTOM,
                            'DrawArrows' => true,
                        ],
                    ],
                    'charts'  => [
                        [
                            'drawable' => ['chart2', 'chart3'],
                            'type' => 'LineChart',
                            'format' => [
                                'DisplayValues' => true,
                                'DisplayG' => 255,
                            ],
                            'font' => [
                                'FontSize' => 20,
                            ],
                        ],
                        [
                            'drawable' => ['chart3'],
                            'type' => 'PlotChart',
                            'format' => [
                                'PlotSize' => 8,
                            ],
                        ],
                    ],
                    'legend' => [
                        'drawable' => ['chart2', 'chart3'],
                        'options' => [
                            50,
                            420,
                            [
                                'FontSize' => 18,
                                'FontR' => 0,
                                'FontG' => 0,
                                'FontB' => 255,
                                'Style' => LEGEND_BOX,
                                'Mode' => LEGEND_HORIZONTAL,
                            ],
                        ],
                    ],
                ],
            ],  //
            [
                2,
                [
                    'points' => [
                        'chart1' => [1, 2, 3, 4, 5],
                        'chart2' => [11, 12, 13, 14, 15],
                        'chart3' => [15, 12, 14, 13, 11],
                    ],
                    'canvas' => [
                        'width' => 700,
                        'height' => 500,
                        'rgba' => '#ffeeddff',
                    ],
                    'chartArea' => [
                        'marginTop' => 100,
                        'marginBottom' => 100,
                        'marginLeft' => 100,
                        'marginRight' => 100,
                        'rgba' => '#ddffeeff'
                    ],
                    'dataset' => [
                        'Abscissa' => ['chart1'],
                    ],
                    'charts'  => [
                        [
                            'drawable' => ['chart3'],
                            'type' => '2DPie',
                            'class' => 'pie',
                            'options' => [
                                300,
                                200,
                                [
                                    'Radius' => 100,
                                    'DrawLabels' => true,
                                ],
                            ],
                            'font' => [
                                'FontSize' => 20,
                            ],
                        ],
                    ],
                    'legend' => [
                        'options' => [
                            50,
                            420,
                            [
                                'FontSize' => 18,
                                'Style' => LEGEND_BOX,
                                'Mode' => LEGEND_HORIZONTAL,
                            ],
                        ],
                    ],
                ],
            ],  //
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('drawLegendProvider')]
    public function drawLegend($i, $data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new CpChartBuilder();

        $this->callPrivateMethod($obj, 'addPoints', [$data]);
        $this->callPrivateMethod($obj, 'drawCanvas', [$data]);
        $this->callPrivateMethod($obj, 'drawChartArea', [$data]);
        $this->callPrivateMethod($obj, 'setDataset', [$data]);

        if (isset($data['scale'])) {
            $this->callPrivateMethod($obj, 'drawScale', [$data]);
        }
        $this->callPrivateMethod($obj, 'drawCharts', [$data]);
        $this->callPrivateMethod($obj, 'drawLegend', [$data]);

        $pImage = $this->getPrivateProperty($obj, 'image');
        $file = "{$this->tmp}drawLegend{$i}.png";
        $pImage->render($file);

        $this->assertEquals(1, 1);
    }

    public static function drawTitleProvider()
    {
        return [
            [
                1,
                [
                    'points' => [
                        'chart1' => [1, 2, 3, 4, 5],
                        'chart2' => [11, 12, 13, 14, 15],
                        'chart3' => [15, 12, 14, 13, 11],
                    ],
                    'canvas' => [
                        'width' => 700,
                        'height' => 500,
                        'rgba' => '#ffeeddff',
                    ],
                    'chartArea' => [
                        'marginTop' => 100,
                        'marginBottom' => 100,
                        'marginLeft' => 100,
                        'marginRight' => 100,
                        'rgba' => '#ddffeeff'
                    ],
                    'dataset' => [
                        'Abscissa' => [
                            'chart1',
                        ],
                        'AbscissaName' => [
                            'TEMPARATURE',
                        ],
                        'SerieOnAxis' => [
                            ['chart2', 0],
                            ['chart3', 1],
                        ],
                        'AxisName' => [
                            [0, 'TEMP'],
                            [1, 'HUMIDITY'],
                        ],
                        'SerieDescription' => [
                            ['chart2', 'CHART2'],
                            ['chart3', 'CHART3'],
                        ],
                    ],
                    'scale' => [
                        'font' => [
                            'R' => 255,
                        ],
                        'format' => [
                            'Pos' => SCALE_POS_TOPBOTTOM,
                            'DrawArrows' => true,
                        ],
                    ],
                    'charts'  => [
                        [
                            'drawable' => ['chart2', 'chart3'],
                            'type' => 'LineChart',
                            'format' => [
                                'DisplayValues' => true,
                                'DisplayG' => 255,
                            ],
                            'font' => [
                                'FontSize' => 20,
                            ],
                        ],
                        [
                            'drawable' => ['chart3'],
                            'type' => 'PlotChart',
                            'format' => [
                                'PlotSize' => 8,
                            ],
                        ],
                    ],
                    'legend' => [
                        'drawable' => ['chart2', 'chart3'],
                        'options' => [
                            50,
                            420,
                            [
                                'FontSize' => 18,
                                'Style' => LEGEND_BOX,
                                'Mode' => LEGEND_HORIZONTAL,
                            ],
                        ],
                    ],
                    'title' => [
                        null,
                        450,
                        'ChartTest',
                        [
                            'Align' => TEXT_ALIGN_TOPMIDDLE,
                            'FontSize' => 40,
                            'R' => 0,
                            'G' => 0,
                            'B' => 255,
                        ],
                    ],
                ],
            ],  //
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('drawTitleProvider')]
    public function drawTitle($i, $data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new CpChartBuilder();

        $this->callPrivateMethod($obj, 'addPoints', [$data]);
        $this->callPrivateMethod($obj, 'drawCanvas', [$data]);
        $this->callPrivateMethod($obj, 'drawChartArea', [$data]);
        $this->callPrivateMethod($obj, 'setDataset', [$data]);

        if (isset($data['scale'])) {
            $this->callPrivateMethod($obj, 'drawScale', [$data]);
        }
        $this->callPrivateMethod($obj, 'drawCharts', [$data]);
        $this->callPrivateMethod($obj, 'drawLegend', [$data]);
        $this->callPrivateMethod($obj, 'drawTitle', [$data]);

        $pImage = $this->getPrivateProperty($obj, 'image');
        $file = "{$this->tmp}drawTitle{$i}.png";
        $pImage->render($file);

        $this->assertEquals(1, 1);
    }

    public static function drawTitle2Provider()
    {
        return [
            [
                1,
                [
                    'points' => [
                        'chart1' => [1, 2, 3, 4, 5],
                        'chart2' => [11, 12, 13, 14, 15],
                        'chart3' => [15, 12, 14, 13, 11],
                    ],
                    'canvas' => [
                        'width' => 700,
                        'height' => 500,
                        'rgba' => '#ffeeddff',
                    ],
                    'title' => [
                        10,
                        20,
                        'text' => 'TEST',
                    ],
                ]
            ], //
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('drawTitle2Provider')]
    public function drawTitle2($i, $data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new CpChartBuilder();

        $this->callPrivateMethod($obj, 'addPoints', [$data]);
        $this->callPrivateMethod($obj, 'drawCanvas', [$data]);
        $this->callPrivateMethod($obj, 'drawTitle', [$data]);

        $pImage = $this->getPrivateProperty($obj, 'image');
        $file = "{$this->tmp}drawTitle2_{$i}.png";
        $pImage->render($file);

        $this->assertEquals(1, 1);
    }

    public static function drawBuildProvider()
    {
        return [
            [
                1,
                [
                    'file' => __DIR__ . '\\tmp\\build.png',
                    'points' => [
                        'chart1' => [1, 2, 3, 4, 5],
                        'chart2' => [11, 12, 13, 14, 15],
                        'chart3' => [15, 12, 14, 13, 11],
                    ],
                    'canvas' => [
                        'width' => 700,
                        'height' => 500,
                        'rgba' => '#ffeeddff',
                    ],
                    'chartArea' => [
                        'marginTop' => 100,
                        'marginBottom' => 100,
                        'marginLeft' => 100,
                        'marginRight' => 100,
                        'rgba' => '#ddffeeff'
                    ],
                    'dataset' => [
                        'Abscissa' => [
                            'chart1',
                        ],
                        'AbscissaName' => [
                            'TEMPARATURE',
                        ],
                        'SerieOnAxis' => [
                            ['chart2', 0],
                            ['chart3', 1],
                        ],
                        'AxisName' => [
                            [0, 'TEMP'],
                            [1, 'HUMIDITY'],
                        ],
                        'SerieDescription' => [
                            ['chart2', 'CHART2'],
                            ['chart3', 'CHART3'],
                        ],
                    ],
                    'scale' => [
                        'font' => [
                            'R' => 255,
                        ],
                        'format' => [
                            'Pos' => SCALE_POS_TOPBOTTOM,
                            'DrawArrows' => true,
                        ],
                    ],
                    'charts'  => [
                        [
                            'drawable' => ['chart2', 'chart3'],
                            'type' => 'LineChart',
                            'format' => [
                                'DisplayValues' => true,
                                'DisplayG' => 255,
                            ],
                            'font' => [
                                'FontSize' => 20,
                            ],
                        ],
                        [
                            'drawable' => ['chart3'],
                            'type' => 'PlotChart',
                            'format' => [
                                'PlotSize' => 8,
                            ],
                        ],
                    ],
                    'legend' => [
                        'drawable' => ['chart2', 'chart3'],
                        'options' => [
                            50,
                            420,
                            [
                                'FontSize' => 18,
                                'Style' => LEGEND_BOX,
                                'Mode' => LEGEND_HORIZONTAL,
                            ],
                        ],
                    ],
                    'title' => [
                        null,
                        450,
                        'ChartTest',
                        [
                            'Align' => TEXT_ALIGN_TOPMIDDLE,
                            'FontSize' => 40,
                            'R' => 0,
                            'G' => 0,
                            'B' => 255,
                        ],
                    ],
                ],
            ],  //
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('drawBuildProvider')]
    public function build($i, $data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new CpChartBuilder();
        $obj->build($data);

        $this->assertEquals(1, 1);
    }

    public static function drawDataTableProvider()
    {
        return [
            [
                [
                    'file' => __DIR__ . '\\tmp\\drawDataTable1.png',
                    'points' => [
                        'chart1' => [1, 2, 3, 4, 5],
                        'chart2' => [11, 12, 13, 14, 15],
                        'chart3' => [15, 12, 14, 13, 11],
                    ],
                    'canvas' => [
                        'width' => 700,
                        'height' => 500,
                        'rgba' => '#ffeeddff',
                    ],
                    'chartArea' => [
                        'marginTop' => 100,
                        'marginBottom' => 100,
                        'marginLeft' => 100,
                        'marginRight' => 100,
                        'rgba' => '#ddffeeff'
                    ],
                    'dataset' => [
                        'Abscissa' => [
                            'chart1',
                        ],
                        'AbscissaName' => [
                            'TEMPARATURE',
                        ],
                        'SerieOnAxis' => [
                            ['chart2', 0],
                            ['chart3', 1],
                        ],
                        'AxisName' => [
                            [0, 'TEMP'],
                            [1, 'HUMIDITY'],
                        ],
                        'SerieDescription' => [
                            ['chart2', 'CHART2'],
                            ['chart3', 'CHART3'],
                        ],
                    ],
                    'scale' => [
                        'font' => [
                            'R' => 255,
                        ],
                        'format' => [
                            'Pos' => SCALE_POS_TOPBOTTOM,
                            'DrawArrows' => true,
                        ],
                    ],
                    'charts'  => [
                        [
                            'drawable' => ['chart2', 'chart3'],
                            'type' => 'LineChart',
                            'format' => [
                                'DisplayValues' => true,
                                'DisplayG' => 255,
                            ],
                            'font' => [
                                'FontSize' => 20,
                            ],
                        ],
                        [
                            'drawable' => ['chart3'],
                            'type' => 'PlotChart',
                            'format' => [
                                'PlotSize' => 8,
                            ],
                        ],
                    ],
                    'legend' => [
                        'drawable' => ['chart2', 'chart3'],
                        'options' => [
                            50,
                            420,
                            [
                                'FontSize' => 18,
                                'Style' => LEGEND_BOX,
                                'Mode' => LEGEND_HORIZONTAL,
                            ],
                        ],
                    ],
                    'title' => [
                        null,
                        450,
                        'ChartTest',
                        [
                            'Align' => TEXT_ALIGN_TOPMIDDLE,
                            'FontSize' => 40,
                            'R' => 0,
                            'G' => 0,
                            'B' => 255,
                        ],
                    ],
                    'dataTable' => [
                        'table' => [
                            10,
                            10,
                            600,
                            490,
                        ],
                        'cell' => [
                            'R' => 0,
                            'G' => 255,
                            'B' => 0,
                            'BorderR' => 255,
                            'BorderG' => 0,
                            'BorderB' => 0,
                        ],
                        'font' => [
                            'FontSize' => 16,
                        ],
                        'padding' => 6,
                    ],
                ],
            ],  //
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('drawDataTableProvider')]
    public function drawDataTable($data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new CpChartBuilder();
        $obj->build($data);

        $this->assertEquals(1, 1);
    }
}
