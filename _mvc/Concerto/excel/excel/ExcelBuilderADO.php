<?php

/**
*   EXCEL builder Ausxilialy Data Object
*
*   @version 230118
*   @caution write()で空行1行で終わる場合、querttableがrefreshエラーとなる
*       この場合、空白1文字でも良いので、ファイルにデータを出す
*/

declare(strict_types=1);

namespace Concerto\excel\excel;

use COM;
use RuntimeException;
use SplFileObject;
use VARIANT;
use Concerto\excel\excel\{
    ExcelBuilderInterface,
    ExcelBuilderTrait
};

abstract class ExcelBuilderADO implements ExcelBuilderInterface
{
    use ExcelBuilderTrait;

    /**
    *   @var string
    */
    protected string $tmpDir;

    /**
    *   @var ?SplFileObject
    */
    protected ?SplFileObject $file = null;

    /**
    *   __construct
    *
    *   @param string $tmpDir
    */
    public function __construct(
        ?string $tmpDir = null
    ) {
        $this->tmpDir = $tmpDir ?? sys_get_temp_dir();
    }

    /**
    *   @inheritDoc
    *
    */
    abstract public function build(
        $excel,
        $book
    );

    /**
    *   ADO(CSV)経由データ作成
    *
    *   @param VARIANT $sheet ワークシートオブジェクト
    *   @param callable $callback 実行関数
    *       $callback(VARIANT Sheet):?VARIANT (Range)
    *   @return static
    */
    protected function ado(
        VARIANT $sheet,
        callable $callback
    ): static {
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
    *   @param mixed[] $fields データ
    *   @return int 出力文字長さ
    */
    protected function write(
        array $fields
    ): int {
        if (!isset($this->file)) {
            throw new RuntimeException(
                "ado file not created"
            );
        }

        if ($fields === []) {
            return 0;
        }

        mb_convert_variables('SJIS', 'UTF-8', $fields);

        $result = $this->file->fputcsv($fields);

        if ($result === false) {
            throw new RuntimeException(
                "write csv error:" . print_r($fields, true)
            );
        }

        return $result;
    }
}
