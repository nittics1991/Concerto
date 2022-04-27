<?php

/**
 *   ChartFactory
 *
 * @version 210615
 *
 * @comment ver2.0のFactory.phpのコピーして改造
 */

declare(strict_types=1);

namespace Concerto\chart\cpchart;

use CpChart\Barcode\Barcode128;
use CpChart\Barcode\Barcode39;
use CpChart\Data;
use CpChart\Image;
use InvalidArgumentException;

/**
 * A simple service class utilizing the Factory design pattern. It has three
 * class specific methods, as well as a generic loader for the chart classes.
 *
 * @author szymach @ http://github.com/szymach
 */
class ChartFactory
{
    /**
    *   @var string
    */
    private $namespace;

    /**
    *   __construct
    *
    *   @param ?string $namespace
    */
    public function __construct(?string $namespace = 'CpChart')
    {
        $this->namespace = $namespace ?? 'CpChart';
    }

    /**
     * Loads a new chart class (scatter, pie etc.). Some classes require instances of
     * Image and Data classes passed into their constructor. These classes are:
     * Bubble, Pie, Scatter, Stock, Surface and Indicator. Otherwise the
     * pChartObject and DataObject parameters are redundant.
     *
     * ATTENTION! SOME OF THE CHARTS NEED TO BE DRAWN VIA A METHOD FROM THE
     * 'Image' CLASS (ex. 'drawBarChart'), NOT THROUGH THIS METHOD! READ THE
     * DOCUMENTATION FOR MORE DETAILS.
     *
     * @param string $chartType   - type of the chart to be loaded (for example 'pie', not 'pPie')
     * @param Image  $chartObject
     * @param Data   $dataObject
     * @return \CpChart\Chart\{$chartType}
     * @throws NotSupportedChartException
     */
    public function newChart(
        $chartType,
        Image $chartObject = null,
        Data $dataObject = null
    ) {
        $className = 'CpChart\\Chart\\' . ucfirst($chartType);

        if (!class_exists($className)) {
            throw new InvalidArgumentException(
                "not found chart class:{$chartType}"
            );
        }
        return new $className($chartObject, $dataObject);
    }

    /**
     * Creates a new Data class with an option to pass the data to form a serie.
     *
     * @param mixed[] $points    - points to be added to serie
     * @param string $serieName - name of the serie
     * @return Data
     */
    public function newData(array $points = [], $serieName = "Serie1")
    {
        $data = new Data();
        if (!empty($points)) {
            $data->addPoints($points, $serieName);
        }
        return $data;
    }

    /**
     * Create a new Image class. It requires the size of axes to be properly
     * constructed.
     *
     * @param int $XSize                 - length of the X axis
     * @param int $YSize                 - length of the Y axis
     * @param Data $DataSet               - Data class populated with points
     * @param bool $TransparentBackground
     * @return Image
     */
    public function newImage(
        $XSize,
        $YSize,
        Data $DataSet = null,
        $TransparentBackground = false
    ) {
        return new Image(
            $XSize,
            $YSize,
            $DataSet,
            $TransparentBackground
        );
    }

    /**
     * Create one of the Barcode classes. Only the number is required (39 or 128),
     * the class name is contructed on the fly. Passing the constructor's parameters
     * is also available, but not mandatory.
     *
     * @param int $number      - Barcode class number (39 or 128)
     * @param string  $BasePath    - optional path for the file containing the class data
     * @param bool $EnableMOD43
     * @return Barcode39|Barcode128
     * @throws InvalidArgumentException
     */
    public function getBarcode($number, $BasePath = "", $EnableMOD43 = false)
    {
        if ($number != 39 && $number != 128) {
            throw new InvalidArgumentException(
                "invalid code number:{$number}"
            );
        }
        $className = "Barcode{$number}";

        return new $className($BasePath, $EnableMOD43);
    }
}
