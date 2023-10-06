<?php

/**
*   EXCEL管理
*
*   @version 230117
*/

declare(strict_types=1);

namespace Concerto\excel\excel;

use COM;
use ErrorException;
use SplFileInfo;
use VARIANT;
use Concerto\excel\excel\ExcelBuilderInterface;

class ExcelManager
{
    /**
    *   @var COM
    */
    protected COM $excel;

    /**
    *   @var VARIANT
    */
    protected VARIANT $book;

    /**
    *   @var string
    */
    protected string $filePath;

    /**
    *   __construct
    *
    *   @param string $filePath EXCELファイルパス
    */
    public function __construct(
        string $filePath
    ) {
        $this->excel = new COM(
            'excel.application',
            null,
            CP_UTF8
        );

        $this->excel->DisplayAlerts = false;

        register_shutdown_function([$this, 'release']);

        $this->book = $this->excel->Workbooks
            ->Open($filePath);

        $this->filePath = $filePath;
    }

    /**
    *   __destruct
    *
    */
    public function __destruct()
    {
        $this->release();
    }

    /**
    *   オブジェクト解放
    *
    *   @return void
    */
    public function release(): void
    {
        if (!isset($this->excel)) {
            return;
        }

        //Prevents error output to EXCEL
        $displayErrors = (string)ini_get('display_errors');

        ini_set('display_errors', '0');

        @$this->excel->DisplayAlerts = false;

        if ($this->excel->Workbooks->Count > 0) {
            foreach ($this->excel->Workbooks as $obj) {
                @$obj->close();
            }
        }
        @$this->excel->Quit();

        unset($this->excel);

        ini_set('display_errors', $displayErrors);
    }

    /**
    *   バックアップファイル作成
    *
    *   @param string $suffix ファイル名接尾辞
    *   @return string バックアップファイルパス
    */
    public function backup(
        string $suffix = null
    ): string {
        $suffix = $suffix ?? '_' . date('Ymd_His');

        $info = new SplFileInfo($this->filePath);

        $filePath = $info->getPath() . '\\' .
            $info->getBasename() .
            "{$suffix}." .
            $info->getExtension();

        $this->excel->DisplayAlerts = false;

        $this->book->SaveCopyAs($filePath);

        return $filePath;
    }

    /**
    *   リネーム(旧ファイルは残す)
    *
    *   @param string $filePath 新ファイルパス
    *   @return static
    */
    public function rename(
        string $filePath
    ): static {
        $this->excel->DisplayAlerts = false;

        $this->book->SaveAs($filePath);

        $this->filePath = $filePath;

        return $this;
    }

    /**
    *   CSV出力
    *
    *   @param string $filePath 出力CSVファイルパス
    *   @param ?string $sheetName シート名
    *   @return static
    */
    public function toCSV(
        string $filePath,
        ?string $sheetName = null
    ): static {
        $this->excel->DisplayAlerts = false;

        if (isset($sheetName)) {
            $this->book->Workbooks($sheetName)
                ->Activate();
        }

        $this->book->SaveAs($filePath, 6);

        return $this;
    }

    /**
    *   帳票作成
    *
    *   @param ExcelBuilderInterface $builder
    *   @return void
    */
    public function report(
        ExcelBuilderInterface $builder
    ): void {
        $this->excel->DisplayAlerts = false;

        $builder->build($this->excel, $this->book);

        $this->book->Save();

        $this->book->Close();

        $this->excel->Quit();

        unset($this->excel);
    }

    /**
    *   読み込み
    *
    *   @param ExcelBuilderInterface $builder
    *   @return void
    */
    public function read(
        ExcelBuilderInterface $builder
    ): void {
        $this->excel->DisplayAlerts = false;

        $builder->build($this->excel, $this->book);

        $this->book->Close();

        $this->excel->Quit();

        unset($this->excel);
    }
}
