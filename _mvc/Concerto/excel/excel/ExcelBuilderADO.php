<?php

/**
*   EXCEL builder Ausxilialy Data Object
*
*   @version 210610
*   @caution write()で空行1行で終わる場合、querttableがrefreshエラーとなる
*       この場合、空白1文字でも良いので、ファイルにデータを出す
*/

declare(strict_types=1);

namespace Concerto\excel\excel;

use RuntimeException;
use SplFileObject;
use Concerto\excel\excel\{
    ExcelBuilderInterface,
    ExcelBuilderTrait
};

abstract class ExcelBuilderADO implements ExcelBuilderInterface
{
    use ExcelBuilderTrait;

    /**
    *   tmp DIR
    *
    *   @var string
    */
    protected $tmpDir;

    /**
    *   ADO file
    *
    *   @var SplFileObject
    */
    protected $file;

    /**
    *   __construct
    *
    *   @param ?string $tmpDir TEMP DIR
    */
    public function __construct($tmpDir = null)
    {
        $this->tmpDir = $tmpDir ?? sys_get_temp_dir();
    }

    /**
    *   {inherit}
    *
    */
    abstract public function build($excel, $book);

    /**
    *   ADO(CSV)経由データ作成
    *
    *   @param mixed $sheet ワークシートオブジェクト
    *   @param callable $callback 実行関数
    *       $callback引数:EXCEL Sheet 戻り値 EXCEL Range|null
    *   @return object $this
    */
    protected function ado($sheet, $callback)
    {
        $name = tempnam($this->tmpDir, 'csv');

        if ($name === false) {
            throw new RuntimeException(
                "can not create csv tempname"
            );
        }

        $this->file = new SplFileObject($name, 'w');
        $this->file->flock(LOCK_EX);

        $range = $callback($sheet);

        $this->file->flock(LOCK_UN);
        $info = $this->file->fstat();
        $size = $info['size'];

        unset($this->file);

        $dns = 'TEXT;' . $name;

        if (isset($range) && $size > 0) {
            $qt = $sheet->QueryTables->Add($dns, $range);
            $qt->Name = 'aaaa';
            $qt->RefreshStyle = 0;
            $qt->RefreshPeriod = 0;
            $qt->TextFilePlatform = 932;
            $qt->TextFileStartRow = 1;
            $qt->TextFileCommaDelimiter = true;
            $qt->AdjustColumnWidth = false;
            $qt->Refresh(false);
            $qt->Delete();
        }
        return $this;
    }

    /**
    *   ADO出力
    *
    *   @param array $fields データ
    *   @return int 出力文字長さ
    */
    protected function write(array $fields): int
    {
        if (is_null($this->file)) {
            throw new RuntimeException("ado file not created");
        }

        if ($fields == []) {
            return 0;
        }

        mb_convert_variables('SJIS', 'UTF-8', $fields);
        $result = $this->file->fputcsv($fields);

        if ($result === false) {
            throw new RuntimeException(
                "write csv error:" . var_export($fields, true)
            );
        }
        return $result;
    }

    /**
    *   $this->adoのcallback
    *   ADO(CSV)経由データ作成処理コールバック
    *
    *   @param Worksheet $sheet
    *   @param callable $callback
    *   @return Range 出力先基点Range
    */
    //protected function function($sheet, $callback);
}
