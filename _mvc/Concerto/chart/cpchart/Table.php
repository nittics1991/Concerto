<?php

/**
 *   Table
 *
 * @version 210903
 */

declare(strict_types=1);

namespace Concerto\chart\cpchart;

use RuntimeException;
use CpChart\{
    Data,
    Image,
};

class Table
{
    /**
     *   data
     *
     * @var mixed[]
     */
    protected $defData = [
        'dataset' => [
            ['', '', '', '', ''],
            ['', '', '', '', ''],
        ],
        'descriptions' =>  [
            //abscissa除く
            '',
        ],
        'table' => [
            //sx,sy,ex,ey
            10,
            10,
            590,
            390,
        ],
        'font' => [
            //@see setFontProperties
            'FontName' => 'c:\\windows\\Fonts\\Arial.ttf',
            'FontSize' => 14,
        ],
        'cell' => [
            //@see drawFilledRectangle[format]
            'R' => 255,
            'G' => 255,
            'B' => 255,
            'BorderR' => 0,
            'BorderG' => 0,
            'BorderB' => 0,
        ],
        'padding' => 4,
    ];

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
     *   setting
     *
     * @var mixed[]
     */
    protected $setting;

    /**
     *   __construct
     *
     * @param Data $data
     * @param Image $image
     */
    public function __construct($data, $image)
    {
        $this->data = $data;
        $this->image = $image;
    }

    /**
     *   render
     *
     * @param mixed[] $params
     * @return void
     */
    public function render(array $params)
    {
        $this->setting = array_replace_recursive(
            $this->defData,
            $params
        );

        $points['sx'] = $points['x'] = $this->setting['table'][0];
        $points['sy'] = $points['y'] = $this->setting['table'][1];
        $points['ex'] = $this->setting['table'][2];
        $points['ey'] = $this->setting['table'][3];
        $points['rangeout'] = false;

        $descriptionCellSize = $this->calcDescriptionCellSize();
        $points['h'] = $descriptionCellSize[1];
        $points['dw'] = $descriptionCellSize[0];
        $dataCellSize = $this->calcDataCellSize();
        $points['w'] = $dataCellSize[0];

        $points = $this->drawDescription($points);
        $points = $this->drawData($points);
    }

    /**
     *   calcDescriptionCellSize
     *
     * @return mixed[] [width, height]
     */
    protected function calcDescriptionCellSize()
    {
        $maxLength = 0;

        foreach ($this->setting['descriptions'] as $text) {
            if (($len = mb_strwidth($text)) > $maxLength) {
                $maxLength = $len;
            }
        }
        return $this->calcCellSize(
            implode(
                '',
                array_fill(0, $maxLength, 'A')
            )
        );
    }

    /**
     *   calcDataCellSize
     *
     * @return mixed[]
     */
    protected function calcDataCellSize()
    {
        $maxLength = 0;

        foreach ($this->setting['dataset'] as $dataset) {
            foreach ($dataset as $val) {
                if (($len = mb_strwidth((string)$val)) > $maxLength) {
                    $maxLength = $len;
                }
            }
        }
        return $this->calcCellSize(
            implode(
                '',
                array_fill(0, $maxLength, 'A')
            )
        );
    }

    /**
     *   calcCellSize
     *
     * @param  string $text
     * @return mixed[] [width, height]
     *   throws RuntimeException
     */
    protected function calcCellSize($text)
    {
        $boundingBox = imagettfbbox(
            $this->setting['font']['FontSize'],
            0,
            $this->setting['font']['FontName'],
            $text
        );

        if ($boundingBox === false) {
            throw new RuntimeException(
                "failure to get bounding box:{$text}"
            );
        }
        $padding = $this->setting['padding'] * 2;

        return [
            abs($boundingBox[2] - $boundingBox[0]) + $padding,
            abs($boundingBox[7] - $boundingBox[1]) + $padding,
        ];
    }

    /**
     *   drawDescription
     *
     * @param mixed[] $points
     * @return mixed[]
     */
    protected function drawDescription(array $points)
    {
        $descriptions = $this->setting['descriptions'];
        array_unshift($descriptions, '');

        foreach ($descriptions as $val) {
            $points = $this->drawCell(
                $val,
                $points,
                $points['dw'],
                'left'
            );
            if ($points['rangeout']) {
                break;
            }
            $points['y'] += $points['h'];
        }
        $points['x'] += $points['dw'];

        return $points;
    }

    /**
     *   drawData
     *
     * @param mixed[] $points
     * @return mixed[]
     */
    protected function drawData(array $points)
    {
        $transversed = call_user_func_array(
            'array_map',
            array_merge([null], $this->setting['dataset'])
        );

        foreach ($transversed as $columns) {
            $points['y'] = $points['sy'];
            $firstRow = true;

            foreach ($columns as $val) {
                $points = $this->drawCell(
                    $val,
                    $points,
                    $points['w'],
                    ($firstRow) ? 'center' : 'right'
                );
                if ($points['rangeout']) {
                    break 2;
                }
                $points['y'] += $points['h'];
                $firstRow = false;
            }
            $points['x'] += $points['w'];
        }
        return $points;
    }

    /**
     *   drawCell
     *
     * @param  string $text
     * @param  mixed[]  $points
     * @param  float  $width
     * @param  string $align  (left/center/right)
     * @return mixed[]
     */
    protected function drawCell(
        $text,
        array $points,
        $width,
        $align = 'right'
    ) {
        if (!$this->isWithInRange($points, $width)) {
            $points['rangeout'] = true;
            $this->drawOverflowMessage($points);
            return $points;
        }

        $this->image->drawFilledRectangle(
            $points['x'],
            $points['y'],
            $points['x'] + $width,
            $points['y'] + $points['h'],
            $this->setting['cell']
        );

        if (!mb_strlen((string)$text)) {
            return $points;
        }

        if ($align == 'left') {
            $padding = $this->setting['padding'];
            $pos = TEXT_ALIGN_TOPLEFT;
        } elseif ($align == 'center') {
            $padding = $width / 2;
            $pos = TEXT_ALIGN_TOPMIDDLE;
        } else {
            $padding = $width - $this->setting['padding'];
            $pos = TEXT_ALIGN_TOPRIGHT;
        }

        $this->image->drawText(
            $points['x'] + $padding,
            $points['y'] + $this->setting['padding'],
            $text,
            array_merge($this->setting['font'], ['Align' => $pos])
        );
        return $points;
    }

    /**
     *   isWithInRange
     *
     * @param  mixed[] $points
     * @param  float $width
     * @return bool
     */
    protected function isWithInRange(array $points, $width)
    {
        return ($points['x'] + $width) <= $points['ex'] &&
            ($points['y'] + $points['h']) <= $points['ey']
        ;
    }

    /**
     *   drawOverflowMessage
     *
     * @param mixed[] $points
     */
    protected function drawOverflowMessage(array $points): void
    {
        $this->image->drawText(
            $points['sx'],
            $points['sy'] - 1,
            'drawing range overflowed',
            [
                'align' => TEXT_ALIGN_BOTTOMLEFT,
                'FontSize' => 8,
            ]
        );
    }
}
