<?php

declare(strict_types=1);

namespace test\Concerto\excel;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\excel\ExcelSheetDirectWriter;
use DateTimeImmutable;

class ExcelSheetDirectWriterTest extends ConcertoTestCase
{
    #[Test]
    public function addData1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file_name =  implode(
            DIRECTORY_SEPARATOR,
            [
                __DIR__,
                'data',
                'ExcelSheetDirectWriter1.xlsx',
            ],
        );

        $sheet_name = 'Sheet1';

        $data1 = [
            //B3::D5
            [
                'id' => 1,
                'name' => '青木',
                'height' => 170.5,
            ],
            [
                'id' => 2,
                'name' => '伊藤',
                'height' => 167.3,
            ],
            [
                'id' => 3,
                'name' => '宇田',
                'height' => 177.7,
            ],
        ];

        //F3::H6
        $data2 = [
            [
                'id' => 13,
                'name' => '加藤',
                'height' => 159.1,
            ],
            [
                'id' => 6,
                'name' => '木村',
                'height' => 163.0,
            ],
            [
                'id' => 9,
                'name' => '栗原',
                'height' => 171.9,
            ],
            [
                'id' => 7,
                'name' => '近藤',
                'height' => 144.3,
            ],
        ];

        $obj = new ExcelSheetDirectWriter(
            $file_name,
            $sheet_name,
            'B3',
        );

        $save_file = $obj->addData($data1)
            ->addData($data2)
            ->save();

        $actual = file_get_contents(
            "zip://{$save_file}#xl/worksheets/sheet1.xml",
        );

        $expects = [
            '.+<row r="3"',
            '.+<row r="6"',
            '.+<c r="B3" t="n"><v>1</v></c>',
            '.+<c r="H6" t="n"><v>144.3</v></c>',
        ];

        foreach ($expects as $expect) {
            $this->assertEquals(
                true,
                mb_ereg_match($expect, $actual),
                "failed value={$expect}",
            );
        }
    }
}
