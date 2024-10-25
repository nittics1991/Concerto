<?php

declare(strict_types=1);

namespace test\Concerto\excel;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\excel\ExcelBook;
use DateTimeImmutable;

class ExcelScenario1Test extends ConcertoTestCase
{
    #[Test]
    public function sameRowsMerge()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file_name = 'ExcelScenario1.xlsx';

        $temp_dir = sys_get_temp_dir() .
            DIRECTORY_SEPARATOR .
            uniqid();

        @mkdir($temp_dir);

        $book1 = new ExcelBook(
            $temp_dir,
            realpath(__DIR__ . "/data/{$file_name}"),
        );

        $sheet_name = 'テスト';

        $sheet = $book1->sheet($sheet_name);

        $data1 = [
            [
                'id' => 1,
                'name' => '青木',
                'height' => 170.5,
                'measurement_day' => new DateTimeImmutable(
                    '2020-07-12',
                ),
            ],
            [
                'id' => 2,
                'name' => '伊藤',
                'height' => 167.3,
                'measurement_day' => new DateTimeImmutable(
                    '2018-12-01',
                ),
            ],
            [
                'id' => 3,
                'name' => '宇田',
                'height' => 177.7,
                'measurement_day' => new DateTimeImmutable(
                    '2025-03-03',
                ),
            ],
        ];

        $sheet->addData('B1', $data1, true);

        $data2 = [
            [
                'id' => 13,
                'name' => '加藤',
                'height' => 159.1,
                'measurement_day' => new DateTimeImmutable(
                    '2029-11-10',
                ),
            ],
            [
                'id' => 6,
                'name' => '木村',
                'height' => 163.0,
                'measurement_day' => new DateTimeImmutable(
                    '2018-12-01',
                ),
            ],
            [
                'id' => 9,
                'name' => '栗原',
                'height' => 171.9,
                'measurement_day' => new DateTimeImmutable(
                    '2025-03-03',
                ),
            ],
        ];

        $sheet->addData('G1', $data2, true);

        $sheet->expandData();

        $book_name = $book1->close();

        $copy_book = $temp_dir .
            DIRECTORY_SEPARATOR .
            uniqid() .
            basename($book_name);

        @mkdir(dirname($copy_book));

        copy($book_name, $copy_book);

        $temp_dir = sys_get_temp_dir() .
            DIRECTORY_SEPARATOR .
            uniqid();

        @mkdir($temp_dir);

        $book2 = new ExcelBook(
            $temp_dir,
            realpath($copy_book),
        );

        $sheet_name = 'テスト';

        $sheet = $book2->loadSheet($sheet_name);

        $actual = $sheet->toArray();

        $expect[1] = [
            2 => 1,
            3 => '青木',
            4 => 170.5,
            5 => new DateTimeImmutable(
                '2020-07-12',
            ),
            7 => 13,
            8 => '加藤',
            9 => 159.1,
            10 => new DateTimeImmutable(
                '2029-11-10',
            ),
        ];

        $expect[2] = [
            2 => 2,
            3 => '伊藤',
            4 => 167.3,
            5 => new DateTimeImmutable(
                '2018-12-01',
            ),
            7 => 6,
            8 => '木村',
            9 => 163.0,
            10 => new DateTimeImmutable(
                '2018-12-01',
            ),
        ];

        $expect[3] = [
            2 => 3,
            3 => '宇田',
            4 => 177.7,
            5 => new DateTimeImmutable(
                '2025-03-03',
            ),
            7 => 9,
            8 => '栗原',
            9 => 171.9,
            10 => new DateTimeImmutable(
                '2025-03-03',
            ),
        ];

        $this->assertEquals(
            $expect,
            $actual,
        );
    }
}
