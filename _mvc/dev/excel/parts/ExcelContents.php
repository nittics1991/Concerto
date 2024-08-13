<?php

/**
*   ExcelContents
*
*   @version 240724
*/

declare(strict_types=1);

namespace dev\excel\parts;

use dev\excel\{
    ExcelArchive,
    ExcelBook,
};
use dev\excel\parts\{
    SheetParts,
    WorkBookParts,
    WorkBookRels,
};

class ExcelContents
{
    /**
    *   @var ExcelArchive
    */
    protected ExcelArchive $archive;

    /**
    *   @var WorkBookParts
    */
    protected WorkBookParts $workBookParts;

    /**
    *   @var WorkBookRels
    */
    protected WorkBookRels $workBookRels;

    /**
    *   @var SheetParts
    */
    protected SheetParts $sheetParts;

    /**
    *   __construct
    *
    *   @param ExcelArchive $archive
    */
    public function __construct(
        ExcelArchive $archive,
    ) {
        $this->archive = $archive;
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
            $sheet_id = $this->findSheetPartsId(
                $sheet_name,
            );

            $sheet_file_name = $this->findSheetFileName(
                $sheet_id,
            );

            $this->writeSheet(
                $sheet_file_name,
                $excelBook
                    // ->sheet("xl/{$sheet_name}")
                    ->sheet($sheet_name)
                    ->toArray(),
            );
        }
    }

    /**
    *   findSheetPartsId
    *
    *   @param string $sheet_name
    *   @return string
    */
    private function findSheetPartsId(
        string $sheet_name,
    ): string {
        $this->workBookParts = $this->workBookParts ??
            new WorkBookParts(
                $this->archive,
            );

        return $this->workBookParts->findSheetPartsId(
            $sheet_name
        );
    }

    /**
    *   findSheetFileName
    *
    *   @param string $sheet_id
    *   @return string
    */
    private function findSheetFileName(
        string $sheet_id,
    ): string {
        $this->workBookRels = $this->workBookRels ??
            new WorkBookRels(
                $this->archive,
            );

        return 'xl/' .
            $this->workBookRels->findSheetFileName(
                $sheet_id,
            );
    }

    /**
    *   writeSheet
    *
    *   @param string $sheet_file_name
    *   @param array<array<int|float|string|\DateTimeInterface>> $data
    *   @return void
    */
    private function writeSheet(
        string $sheet_file_name,
        array $data,
    ): void {
        $this->sheetParts = $this->sheetParts ??
            new SheetParts(
                $this->archive,
                $sheet_file_name,
            );

        $this->sheetParts->save(
            $data,
        );
    }

    /**
    *   loadSheetData
    *
    *   @param string $sheet_name
    *   @return array<array<int|float|string|\DateTimeInterface>>
    */
    public function loadSheetData(
        string $sheet_name,
    ): array {
        $sheet_id = $this->findSheetPartsId(
            $sheet_name,
        );

        $sheet_file_name = $this->findSheetFileName(
            $sheet_id,
        );

        return $this->doLoadSheetData(
            $sheet_file_name,
        );
    }

    /**
    *   doLoadSheetData
    *
    *   @param string $sheet_file_name
    *   @return array<array<int|float|string|\DateTimeInterface>>
    */
    private function doLoadSheetData(
        string $sheet_file_name,
    ): array {
        $this->sheetParts = $this->sheetParts ??
            new SheetParts(
                $this->archive,
                $sheet_file_name,
            );

        return $this->sheetParts->loadData();
    }
}
