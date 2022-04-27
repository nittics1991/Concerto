<?php

/**
 *   CpChartBuilder
 *
 * @version 200311
 */

declare(strict_types=1);

namespace Concerto\chart\cpchart;

use CpChart\Data;
use CpChart\Image;
use Concerto\chart\cpchart\{
    ChartFactory,
    Table
};

class CpChartBuilder
{
    /**
     *   factory
     *
     * @var string[]
     */
    protected $recipes = [
        'points' => 'addPoints',
        'canvas' => 'drawCanvas',
        'afterCanvas' => 'afterCanvas',
        'chartArea' => 'drawChartArea',
        'afterChartArea' => 'afterChartArea',
        'dataset' => 'setDataset',
        'scale' => 'drawScale',
        'afterScale' => 'afterScale',
        'palette' => 'setPalette',
        'charts' => 'drawCharts',
        'afterCharts' => 'afterCharts',
        'legend' => 'drawLegend',
        'title' => 'drawTitle',
        'dataTable' => 'drawDataTable',
        'afterDataTable' => 'afterDataTable',
        'file' => 'render',
    ];

    /**
     *   factory
     *
     * @var ChartFactory
     */
    protected $factory;

    /**
     *   data
     *
     * @var Data
     */
    protected $data;

    /**
     *   image
     *
     * @var Image
     */
    protected $image;

    /**
     *   chartObject
     *
     * @var object
     */
    protected $chartObject;

    /**
     *   __construct
     */
    public function __construct()
    {
        $this->factory = new ChartFactory();
    }

    /**
     *   build
     *
     * @param mixed[] $dataset
     * @return void
     */
    public function build(array $dataset)
    {
        foreach ($this->recipes as $key => $method) {
            if (isset($dataset[$key]) && !empty($dataset[$key])) {
                $this->$method($dataset);
            }
        }
    }

    /**
     *   addPoints
     *
     * @param mixed[] $dataset
     *       ['chart1' => [1, 2, 3], 'chart2' => [5, 6, 7]]
     * @return void
     */
    protected function addPoints(array $dataset)
    {
        $this->data = $this->factory->newData();

        foreach ($dataset['points'] as $name => $data) {
            $this->data->addPoints($data, $name);
        }
    }

    /**
     *   drawCanvas
     *
     * @param mixed[] $dataset
     *       ['width' => 800, 'height' => 600, 'rgba' => '#ee3399ff']
     */
    protected function drawCanvas(array $dataset)
    {
        $params = $dataset['canvas'];

        $this->image = $this->factory->newImage(
            $params['width'],
            $params['height'],
            $this->data,
            true
        );

        if (isset($params['rgba'])) {
            $this->image->drawFilledRectangle(
                0,
                0,
                $params['width'],
                $params['height'],
                $this->rgbaToAarray(
                    $params['rgba'],
                    ['R', 'G', 'B', 'Alpha']
                )
            );
        }
    }

    /**
     *   afterCanvas
     *
     * @param mixed[] $dataset
     *       callableの引数は(factory, data, image, this)
     */
    protected function afterCanvas(array $dataset)
    {
        call_user_func(
            $dataset['afterCanvas'],
            $this->factory,
            $this->data,
            $this->image,
            $this
        );
    }

    /**
     *   drawChartArea
     *
     * @param mixed[] $dataset
     *       ['marginTop' => 10, 'marginBottom' => 10,
     *       'marginLeft' => 20, 'marginRight' => 30, 'rgba'=> '#ee3399ff']
     */
    protected function drawChartArea(array $dataset)
    {
        $params = $dataset['chartArea'];

        $this->image->setGraphArea(
            $params['marginLeft'],
            $params['marginTop'],
            $dataset['canvas']['width'] - $params['marginRight'],
            $dataset['canvas']['height'] - $params['marginBottom']
        );

        if (isset($params['rgba'])) {
            $this->image->drawFilledRectangle(
                $params['marginLeft'],
                $params['marginTop'],
                $dataset['canvas']['width'] - $params['marginRight'],
                $dataset['canvas']['height'] - $params['marginBottom'],
                $this->rgbaToAarray($params['rgba'], ['R', 'G', 'B', 'Alpha'])
            );
        }
    }

    /**
     *   afterChartArea
     *
     * @param mixed[] $dataset
     *       callableの引数は(factory, data, image, this)
     */
    protected function afterChartArea(array $dataset)
    {
        call_user_func(
            $dataset['afterChartArea'],
            $this->factory,
            $this->data,
            $this->image,
            $this
        );
    }

    /**
     *   setDataset
     *
     * @param mixed[] $dataset
     *       dataset functionの内、setXXXが対象
     *       keyは「set」を除いたXXX, valは配列で引数(複数可)
     *       [['abscissa' => ['chart2']],
     *          ['AxisUnit' => [[1, '%'], [2, '℃']]]]
     */
    protected function setDataset(array $dataset)
    {
        foreach ($dataset['dataset'] as $name => $function) {
            $method = "set{$name}";

            foreach ($function as $params) {
                if (!is_array($params)) {
                    $params = [$params];
                }
                call_user_func_array([$this->data, $method], $params);
            }
        }
    }

    /**
     *   drawScale
     *
     * @param mixed[] $dataset
     *       drawScaleの引数
     *       ['Pos' => SCALE_POS_TOPBOTTOM, LabelRotation => 90]
     */
    protected function drawScale(array $dataset)
    {
        $params = $dataset['scale'];

        if (isset($params['font'])) {
            $this->setFont($params['font']);
        }

        call_user_func_array(
            [$this->image, 'drawScale'],
            [$params['format']]
        );
    }

    /**
     *   afterScale
     *
     * @param mixed[] $dataset
     *       callableの引数は(factory, data, image, this)
     */
    protected function afterScale(array $dataset)
    {
        call_user_func(
            $dataset['afterScale'],
            $this->factory,
            $this->data,
            $this->image,
            $this
        );
    }

    /**
     *   setPalette
     *
     * @param mixed[] $dataset
     */
    protected function setPalette(array $dataset)
    {
        $this->data->loadPalette(
            realpath($dataset['palette']),
            true
        );
    }

    /**
     *   drawCharts
     *
     * @param mixed[] $dataset
     *       ['drawable' => ['chart1', 'chart3'], 'type' => '2DPie', ...]
     * @caution   doDrawChart, doExtensionDrawChart
     */
    protected function drawCharts(array $dataset)
    {
        foreach ($dataset['charts'] as $chart) {
            $this->doSetDrawable($chart['drawable'], $dataset);

            if (isset($chart['font'])) {
                $this->setFont($chart['font']);
            }

            if (isset($chart['class'])) {
                $this->doDrawExtensionChart($chart);
            } else {
                $this->doDrawChart($chart);
            }
        }
    }

    /**
     *   doSetDrawable
     *
     * @param mixed[] $drawable
     * @param mixed[] $dataset
     */
    protected function doSetDrawable(array $drawable, array $dataset)
    {
        $chartNames = array_keys($dataset['points']);

        $enabled = (isset($drawable)) ?
            $drawable : $chartNames;

        $dataStruct = $this->data->getData();
        if (isset($dataStruct['Abscissa'])) {
            unset($enabled[$dataStruct['Abscissa']]);
        }

        $disabled = array_diff($chartNames, $enabled);

        foreach ($enabled as $name) {
            $this->data->setSerieDrawable($name, true);
        }

        foreach ($disabled as $name) {
            $this->data->setSerieDrawable($name, false);
        }
    }

    /**
     *   doDrawChart
     *
     * @param mixed[] $chart
     *       ['format' => ['DisplayValues' => true, 'DisplayOffset' => 2]]
     */
    protected function doDrawChart(array $chart)
    {
        $format = (isset($chart['format'])) ? $chart['format'] : [];
        $method = "draw{$chart['type']}";
        call_user_func_array([$this->image, $method], [$format]);
    }

    /**
     *   doDrawExtensionChart
     *
     * @param mixed[] $chart
     *       ['class' => 'pie', options => [200, 100, ['Radius' => 80]]
     */
    protected function doDrawExtensionChart(array $chart)
    {
        $this->chartObject = $this->factory->newChart(
            $chart['class'],
            $this->image,
            $this->data
        );
        $options = (isset($chart['options'])) ? $chart['options'] : [];
        $method = "draw{$chart['type']}";
        call_user_func_array([$this->chartObject, $method], $options);
    }

    /**
     *   afterCharts
     *
     * @param mixed[] $dataset
     *       callableの引数は(factory, data, image, this)
     */
    protected function afterCharts(array $dataset)
    {
        call_user_func(
            $dataset['afterCharts'],
            $this->factory,
            $this->data,
            $this->image,
            $this
        );
    }

    /**
     *   drawLegend
     *
     * @param mixed[] $dataset
     *       ['drawable' => ['chart2', 'chart3'],
     *       'options' => [420, 100, [FontName => 'Arias', FontSize => 12]]]
     */
    protected function drawLegend(array $dataset)
    {
        if (isset($dataset['legend']['drawable'])) {
            $this->doSetDrawable($dataset['legend']['drawable'], $dataset);
        }

        $callback = [$this->image, 'drawLegend'];

        if (isset($dataset['charts'])) {
            foreach ($dataset['charts'] as $chart) {
                if (mb_stripos($chart['type'], 'pie') !== false) {
                    $callback = [$this->chartObject, 'drawPieLegend'];
                    break;
                } elseif (mb_stripos($chart['type'], 'scatter') !== false) {
                    $callback = [$this->chartObject, 'drawScatterLegend'];
                    break;
                }
            }
        }

        call_user_func_array(
            $callback,
            $dataset['legend']['options']
        );
    }

    /**
     *   drawTitle
     *
     * @param mixed[] $dataset
     *       [null, 10, TITLE, [FontName => 'Arias', FontSize => 12]]
     * @caution   drawText (if x=null then ['canvas']['width'] / 2)
     *       ただしキー'text'は上記TITLEに置き換える
     */
    protected function drawTitle(array $dataset)
    {
        $params = $dataset['title'];

        if ($params[0] == null) {
            $params[0] = $dataset['canvas']['width'] / 2;
        }

        if (isset($params['text'])) {
            $params[2] = $params['text'];
            unset($params['text']);
        }
        call_user_func_array([$this->image, 'drawText'], $params);
    }

    /**
     *   drawDataTable
     *
     * @param mixed[] $dataset
     *       ['table' => [50,520,550,580,['R' => 255], @caution drawFilledRectangle
     *       'cell' => [wiight => 2],   @caution drawLineのformat
     *       'font' => ['FontSize' => 12]    @caution setFontProperties
     *       'padding' => 4,
     */
    protected function drawDataTable(array $dataset)
    {
        $params = $dataset['dataTable'];

        $data = $this->data->getData();
        $points = $dataset['points'];
        $descriptions = [];

        if (isset($data['Abscissa'])) {
            unset($points[$data['Abscissa']]);
        }

        $params['dataset'] = array_values(
            array_merge(
                [$dataset['points'][$data['Abscissa']]],
                $points
            )
        );

        foreach ($data['Series'] as $name => $list) {
            if ($name != $data['Abscissa']) {
                $descriptions[] = $list['Description'];
            }
        }
        $params['descriptions'] = $descriptions;
        $table = new Table($this->data, $this->image);
        $table->render($params);
    }

    /**
     *   afterDataTable
     *
     * @param mixed[] $dataset
     *       callableの引数は(factory, data, image, this)
     */
    protected function afterDataTable(array $dataset)
    {
        call_user_func(
            $dataset['afterDataTable'],
            $this->factory,
            $this->data,
            $this->image,
            $this
        );
    }

    /**
     *   render
     *
     * @param mixed[] $dataset
     */
    protected function render(array $dataset)
    {
        $this->image->render($dataset['file']);
    }

    /**
     *   setFont
     *
     * @params mixed[] $params
     *       ['FontSize' => 12]
     * @caution    setFontProperties
     */
    protected function setFont(array $params)
    {
        call_user_func_array(
            [$this->image, 'setFontProperties'],
            [$params]
        );
    }

    /**
     *   rgbaToAarray
     *
     * @param string $rgba #RRGGBBAA
     * @params mixed[] $names
     * @return array [intR, intG, intB, intA] or array_combine($col, $names)
     */
    protected function rgbaToAarray($rgba, $names = [])
    {
        $col = [
            hexdec(mb_substr($rgba, 1, 2)),
            hexdec(mb_substr($rgba, 3, 2)),
            hexdec(mb_substr($rgba, 5, 2)),
            (int)(hexdec(mb_substr($rgba, 7, 2)) / 255 * 100)
        ];

        return ($names == []) ?
            $col
            : (array)array_combine($names, $col);
    }
}
