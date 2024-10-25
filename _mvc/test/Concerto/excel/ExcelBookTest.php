<?php

declare(strict_types=1);

namespace test\Concerto\excel;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\excel\ExcelBook;

class ExcelBookTest extends ConcertoTestCase
{
    public static function createTempDir(): string
    {
        $tmp = implode(
            DIRECTORY_SEPARATOR,
            [
                sys_get_temp_dir(),
                uniqid(),
            ],
        );

        mkdir($tmp);

        return $tmp;
    }

    public static function copyTemplateFile(
        string $tempdir,
        string $template_file_name,
    ): string {
        $src = implode(
            DIRECTORY_SEPARATOR,
            [
                __DIR__,
                'data',
                $template_file_name,
            ],
        );

        return $src;
    }

    #[Test]
    public function copyTemplate()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file_name = 'ExcelBook1.xlsx';

        $tmp_dir = self::createTempDir();

        $work_file = self::copyTemplateFile(
            $tmp_dir,
            $file_name,
        );

        $obj = new ExcelBook(
            $tmp_dir,
            $work_file,
        );

        $this->assertEquals(
            true,
            file_exists(
                $tmp_dir .
                DIRECTORY_SEPARATOR .
                $file_name,
            )
        );
    }

    #[Test]
    public function sheet()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file_name = 'ExcelBook1.xlsx';

        $tmp_dir = self::createTempDir();

        $work_file = self::copyTemplateFile(
            $tmp_dir,
            $file_name,
        );

        $obj = new ExcelBook(
            $tmp_dir,
            $work_file,
        );

        $sheet1 = $obj->sheet('シート1');

        $this->assertEquals(
            ['シート1'],
            $obj->getSheetNames(),
        );

        $sheet1a = $obj->sheet('シート1');

        $this->assertEquals(
            true,
            $sheet1 == $sheet1a,
        );

        $sheet2 = $obj->sheet('シート2');

        $this->assertEquals(
            ['シート1', 'シート2'],
            $obj->getSheetNames(),
        );
    }


    #[Test]
    public function close1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file_name = 'ExcelBook1.xlsx';

        $tmp_dir = self::createTempDir();

        $work_file = self::copyTemplateFile(
            $tmp_dir,
            $file_name,
        );

        $obj = new ExcelBook(
            $tmp_dir,
            $work_file,
        );

        $book_path = $obj->close();

        $this->assertEquals(
            $tmp_dir .
                DIRECTORY_SEPARATOR .
                $file_name,
            $book_path,
        );
    }

    #[Test]
    public function loadSheet()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file_name = 'ExcelBook1.xlsx';

        $tmp_dir = self::createTempDir();

        $work_file = self::copyTemplateFile(
            $tmp_dir,
            $file_name,
        );

        $obj = new ExcelBook(
            $tmp_dir,
            $work_file,
        );

        $sheet = $obj->loadSheet('シート1');

        $expect = [
            1 => [
                1 => 'A',
                2 => 11,
                3 => 111.11,
            ],
            2 => [
                1 => 'B',
                2 => 12,
                3 => 222.22,
            ],
            3 => [
                1 => 'C',
                2 => 13,
                3 => 333.33,
            ],
            4 => [
                1 => 'D',
                2 => 14,
                3 => 444.44,
            ],
        ];

        $this->assertEquals(
            $expect,
            $sheet->toArray(),
        );
    }
}
