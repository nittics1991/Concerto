<?php

/**
*   CellDataWriter
*
*   @version 240919
*/

declare(strict_types=1);

namespace Concerto\excel\writer;

use DateTimeInterface;
use RuntimeException;
use Concerto\excel\{
    ExcelAddress,
    ExcelArchive,
    ExcelBook,
};
use Concerto\excel\parts\{
    ExcelContents,
    ExcelNode,
    SharedStrings,
    SheetParts,
};

class CellDataWriter
{
    /**
    *   @var ExcelArchive
    */
    protected ExcelArchive $excelArchive;

    /**
    *   @var ExcelContents
    */
    protected ExcelContents $excelContents;

    /**
    *   @var SharedStrings
    */
    protected SharedStrings $sharedStrings;

    /**
    *   __construct
    *
    *   @param ExcelArchive $excelArchive
    */
    public function __construct(
        ExcelArchive $excelArchive,
    ) {
        $this->excelArchive = $excelArchive;

        $this->excelContents = new ExcelContents(
            $this->excelArchive
        );

        $this->sharedStrings = $this->excelContents
            ->getSharedStrings();
    }

    /**
    *   save
    *
    *   @param ExcelBook $excelBook
    *   @return void
    */
    public function save(
        ExcelBook $excelBook,
    ): void {
        $sheet_names = $excelBook->getSheetNames();

        foreach ($sheet_names as $sheet_name) {
            $sheetParts = $this->excelContents
                ->getSheetParts($sheet_name);

            $this->writeSheet(
                $sheetParts,
                $excelBook
                    ->sheet($sheet_name)
                    ->toArray(),
            );
        }

        $this->sharedStrings->close();
    }

    /**
    *   writeSheet
    *
    *   @param SheetParts $sheetParts
    *   @param array<array<int|float|string|\DateTimeInterface>> $data
    *   @return void
    */
    private function writeSheet(
        SheetParts $sheetParts,
        array $data,
    ): void {
        $excel_nodes = [];

        foreach ($data as $row_no => $row) {
            $excel_nodes[] = $this->createRowData(
                $row_no,
                $row,
            );
        }

        $sheetParts->addSheetData(
            $excel_nodes,
        );
    }

    /**
    *   createRowData
    *
    *   @param int $row_no
    *   @param array<int|float|string|DateTimeInterface> $row
    *   @return ExcelNode
    */
    private function createRowData(
        int $row_no,
        array $row,
    ): ExcelNode {
        $rowNode = new ExcelNode();
        $rowNode->name = 'row';
        $rowNode->attribute = [
            'r' => (string)$row_no,
        ];

        foreach ($row as $column_no => $column) {
            $cellNode = $this->createCellData(
                $row_no,
                $column_no,
                $column,
            );

            if ($cellNode !== null) {
                $rowNode->children[] = $cellNode;
            }
        }

        return $rowNode;
    }

    /**
    *   createCellData
    *
    *   @param int $row_no
    *   @param int $column_no
    *   @param mixed $column
    *   @return ?ExcelNode
    */
    private function createCellData(
        int $row_no,
        int $column_no,
        mixed $column,
    ): ?ExcelNode {
        if ($column === null) {
            return null;
        }

        $address = ExcelAddress::locationToAddress(
            [$row_no, $column_no],
        );

        $cellNode = new ExcelNode();
        $cellNode->name = 'c';
        $cellNode->attribute = [
            'r' => $address,
        ];

        $valueNode = new ExcelNode();
        $valueNode->name = 'v';

        $cellNode->children[0] = $valueNode;

        if ($column instanceof DateTimeInterface) {
            $cellNode->attribute['t'] = 'd';
            $cellNode->children[0]->text =
                $column->format(
                    DateTimeInterface::ATOM,
                );

            return $cellNode;
        } elseif (is_int($column) || is_float($column)) {
            $cellNode->attribute['t'] = 'n';
            $cellNode->children[0]->text = (string)$column;

            return $cellNode;
        } elseif (is_string($column)) {
            $shared_index = $this->findBySharedStringNo(
                $column,
            );

            $cellNode->attribute['t'] = 's';
            $cellNode->children[0]->text = (string)$shared_index;

            return $cellNode;
        }

        throw new RuntimeException(
            "data type error:row={$row_no},column={$column_no}",
        );
    }

    /**
    *   findBySharedStringNo
    *
    *   @param string $value
    *   @return int
    */
    private function findBySharedStringNo(
        string $value,
    ): int {
        $pos = $this->sharedStrings->stringNo($value);

        if ($pos !== null) {
            return $pos;
        }

        return $this->sharedStrings->addString($value);
    }
}
