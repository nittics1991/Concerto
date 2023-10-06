<?php

/**
*   EXCEL builder library
*
*   @version 221208
*/

declare(strict_types=1);

namespace Concerto\excel\excel;

use RuntimeException;
use VARIANT;

trait ExcelBuilderTrait
{
    /**
    *   シートのカラム幅自動調整
    *
    *   @param VARIANT $sheet
    *   @return static
    */
    protected function fitColumnWidth(
        VARIANT $sheet
    ): static {
        $sheet->UsedRange->EntireColumn->AutoFit();

        return $this;
    }

    /**
    *   指定レンジの値を二次元配列に代入
    *
    *   @param VARIANT $range
    *   @paran ?int $col 列数
    *   @return mixed[]
    */
    protected function rangeToMartix(
        VARIANT $range,
        ?int $col = null
    ): array {
        $columnCount = $col ??
            $range->Columns->Count;

        $all_items = [];

        $items = [];

        $i = 1;

        foreach ($range as $cell) {
            $items[] = $cell->Value;

            if ($i == $columnCount) {
                $all_items[] = $items;
                $items = [];
                $i = 1;
            } else {
                $i++;
            }
        }

        if ($i !== 1) {
            throw new RuntimeException(
                "column size mismatch"
            );
        }

        return $all_items;
    }

    /**
    *   レンジデータ書き込み
    *
    *   @param VARIANT $sheet
    *   @param VARIANT $baseRange 基準Range
    *   @param (int|float|string\null)[][] $dataset
    *   @return static
    */
    protected function writeRange(
        VARIANT $sheet,
        VARIANT $baseRange,
        array $dataset
    ): static {
        $columnLength = isset($dataset[0]) &&
            is_array($dataset[0]) ?
            count($dataset[0]) : 0;

        $i = 0;

        foreach ($dataset as $list) {
            $v = new \VARIANT(
                mb_convert_encoding(
                    $list,
                    'SJIS',
                    'utf-8'
                )
            );

            $sheet->Range(
                $baseRange->Offset($i, 0),
                $baseRange->Offset($i, $columnLength - 1)
            )->Value = $v;

            $i++;
        }

        return $this;
    }
}
