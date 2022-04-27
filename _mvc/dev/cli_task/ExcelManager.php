<?php

/**
*   EXCEL管理
*
*   @version 191227
*/

declare(strict_types=1);

namespace dev\excel\excel;

use COM;
use ErrorException;
use SplFileInfo;
use dev\excel\excel\ExcelBuilderInterface;

class ExcelManager
{
    /**
    *   EXCEL object
    *
    *   @var resource
    */
    protected $excel;

    /**
    *   EXCEL BOOK object
    *
    *   @var resource
    */
    protected $book;

    /**
    *   EXCEL ファイルパス
    *
    *   @var string
    */
    protected $filePath;

    /**
    *   __construct
    *
    *   @param string $filePath EXCELファイルパス
    */
    public function __construct($filePath)
    {
        $this->excel = new COM('excel.application', null, CP_UTF8);
        $this->excel->DisplayAlerts = false;

        register_shutdown_function([$this, 'release']);

        //エラーでrelease()を実行するが、close(),quit()は動作していなそう
        // $self = $this;
        // set_error_handler(
            // function($no, $message, $file, $line) use ($self) {
                // $self->release();
                // throw new ErrorException($message, 0, $no, $file, $line);
            // }
        // );

        $this->book = $this->excel->Workbooks->Open($filePath);
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
    public function release()
    {
        if (is_null($this->excel)) {
            return;
        }

        //Prevents error output to EXCEL
        $displayErrors = ini_get('display_errors');
        ini_set('display_errors', '0');

        @$this->excel->DisplayAlerts = false;

        if ($this->excel->Workbooks->Count > 0) {
            foreach ($this->excel->Workbooks as $obj) {
                @$obj->close();
            }
        }
        @$this->excel->Quit();

        ini_set('display_errors', $displayErrors);
    }

    /**
    *   バックアップファイル作成
    *
    *   @param ?string $suffix ファイル名接尾辞
    *   @return string バックアップファイルパス
    */
    public function backup($suffix = null)
    {
        $suffix = $suffix ?? '_' . date('Ymd_His');

        $info = new SplFileInfo($this->filePath);
        $filePath = $info->getPath() . '\\'
            . $info->getBasename()
            . "{$suffix}."
            . $info->getExtension();

        $this->excel->DisplayAlerts = false;
        $this->book->SaveCopyAs($filePath);
        return $filePath;
    }

    /**
    *   リネーム(旧ファイルは残す)
    *
    *   @param string $filePath 新ファイルパス
    *   @return this
    */
    public function rename($filePath)
    {
        $this->excel->DisplayAlerts = false;
        $this->book->SaveAs($filePath);
        $this->filePath = $filePath;
        return $this;
    }

    /**
    *   CSV出力
    *
    *   @param string $filePath 出力CSVファイルパス
    *   @param string $sheetName シート名
    *   @return $this
    */
    public function toCSV($filePath, $sheetName = null)
    {
        $this->excel->DisplayAlerts = false;

        if (isset($sheetName)) {
            $this->book->Workbooks($sheetName)->Activate();
        }

        $this->book->SaveAs($filePath, 6);
        return $this;
    }

    /**
    *   帳票作成
    *
    *   @param ExcelBuilderInterface $recipe
    *   @return void
    */
    public function report(ExcelBuilderInterface $recipe)
    {
        $this->excel->DisplayAlerts = false;
        $recipe->build($this->excel, $this->book);


        var_dump($this)

        $this->book->Save();

        var_dump("2.{$this->filePath}")

        $this->book->Close();

        var_dump("3.{$this->filePath}")

        $this->excel->Quit();

        var_dump("4.{$this->filePath}")

        $this->excel = null;
    }

    /**
    *   読み込み
    *
    *   @param ExcelBuilderInterface $recipe
    *   @return void
    */
    public function read(ExcelBuilderInterface $recipe)
    {
        $this->excel->DisplayAlerts = false;
        $recipe->build($this->excel, $this->book);

        $this->book->Close();
        $this->excel->Quit();
        $this->excel = null;
    }
}
