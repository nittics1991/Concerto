<?php

declare(strict_types=1);

namespace test\Concerto\excel\parts;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\excel\writer\CellDataWriter;
use test\Concerto\excel\parts\ExcelPartsHelperTrait;
use Concerto\excel\parts\ExcelNode;
use Concerto\excel\parts\SheetParts;
use DateTimeImmutable;
use ZipArchive;

class CellDataWriterTest extends ConcertoTestCase
{
    use ExcelPartsHelperTrait;

    public static function findBySharedStringNoProvider()
    {
        return [
            ['B', 1],
            ['伊藤', 6],
            ["東京都\n府中市", 5],
        ];
    }

    #[Test]
    #[DataProvider('findBySharedStringNoProvider')]
    public function findBySharedStringNo(
        string $value,
        int $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'CellDataWriter1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new CellDataWriter(
            $archive,
        );

        $actual = $this->callPrivateMethod(
            $obj,
            'findBySharedStringNo',
            [$value],
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public static function createCellDataProvider()
    {
        //数値
        $node[0] = new ExcelNode();
        $node[0]->name = 'c';
        $node[0]->attribute = [
            'r' => 'H3',
            't' => 'n',
        ];

        $node_child1[0] = new ExcelNode();
        $node_child1[0]->name = 'v';
        $node_child1[0]->text = '9999';

        $node[0]->children[0] = $node_child1[0];

        //日付
        $node[1] = new ExcelNode();
        $node[1]->name = 'c';
        $node[1]->attribute = [
            'r' => 'AA13',
            't' => 'd',
        ];

        $node_child1[1] = new ExcelNode();
        $node_child1[1]->name = 'v';
        $node_child1[1]->text = '2134-03-27T12:34:56+09:00';

        $node[1]->children[0] = $node_child1[1];

        //文字列
        $node[2] = new ExcelNode();
        $node[2]->name = 'c';
        $node[2]->attribute = [
            'r' => 'BA23',
            't' => 's',
        ];

        $node_child1[2] = new ExcelNode();
        $node_child1[2]->name = 'v';

        $string[2] = implode(
            '\\r',
            [
                'この文章は',
                '改行しました',
                '特殊文字5文字はエスケープする',
                '<>&"' . "'",
            ],
        );

        $node_child1[2]->text = (string)14;

        $node[2]->children[0] = $node_child1[2];

        return [
            [
                3,
                8,
                9999,
                $node[0],
            ],
            [
                13,
                27,
                new DateTimeImmutable('2134-03-27 12:34:56'),
                $node[1],
            ],
            [
                23,
                53,
                $string[2],
                $node[2],
            ],
        ];
    }

    #[Test]
    #[DataProvider('createCellDataProvider')]
    public function createCellData(
        int $row_no,
        int $column_no,
        mixed $column,
        ?ExcelNode $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'CellDataWriter1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new CellDataWriter(
            $archive,
        );

        $actual = $this->callPrivateMethod(
            $obj,
            'createCellData',
            [$row_no, $column_no, $column],
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public static function createRowDataProvider()
    {
        //col=H
        $row[8] = 9999;
        //col=AA
        $row[27] = new DateTimeImmutable('2134-03-27 12:34:56');
        //col=BA
        $row[53] = implode(
            '\\r',
            [
                'この文章は',
                '改行しました',
                '特殊文字5文字はエスケープする',
                '<>&"' . "'",
            ],
        );

        $strring[0] = implode(
            '\\r',
            [
                'この文章は',
                '改行しました',
                '特殊文字5文字はエスケープする',
                '&lt;&gt;&amp;&quot;&apos;',
            ],
        );

        $node = new ExcelNode();
        $node->name = 'row';
        $node->attribute = [
            'r' => '12',
        ];

        $column[0] = new ExcelNode();
        $column[0]->name = 'c';
        $column[0]->attribute = [
            'r' => 'H12',
            't' => 'n',
        ];

        $value[0] = new ExcelNode();
        $value[0]->name = 'v';
        $value[0]->text = '9999';

        $column[0]->children[0] = $value[0];

        $column[1] = new ExcelNode();
        $column[1]->name = 'c';
        $column[1]->attribute = [
            'r' => 'AA12',
            't' => 'd',
        ];

        $value[1] = new ExcelNode();
        $value[1]->name = 'v';
        $value[1]->text = '2134-03-27T12:34:56+09:00';

        $column[1]->children[0] = $value[1];

        $column[2] = new ExcelNode();
        $column[2]->name = 'c';
        $column[2]->attribute = [
            'r' => 'BA12',
            't' => 's',
        ];

        $value[2] = new ExcelNode();
        $value[2]->name = 'v';
        $value[2]->text = '14';

        $column[2]->children[0] = $value[2];

        $node->children = $column;

        return [
            [
                12,
                $row,
                $node,
            ],
        ];
    }

    #[Test]
    #[DataProvider('createRowDataProvider')]
    public function createRowData(
        int $row_no,
        array $row,
        ExcelNode $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'CellDataWriter1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new CellDataWriter(
            $archive,
        );

        $actual = $this->callPrivateMethod(
            $obj,
            'createRowData',
            [$row_no, $row,],
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    #[Test]
    public function writeSheet()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'CellDataWriter1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new CellDataWriter(
            $archive,
        );

        $sheet_name = 'xl/worksheets/sheet1.xml';

        $sheetParts = new SheetParts(
            $archive,
            $sheet_name,
        );

        $data[11][3] = 9999;
        $data[11][4] = new DateTimeImmutable(
            '2029-11-10 12:34:56+0900'
        );
        $data[11][5] = "佐々木";

        $data[12][3] = 3.1415;
        $data[12][4] = new DateTimeImmutable(
            '2029-2-28 03:45:12+0000'
        );
        $data[12][5] = "清水";

        $expects = [
            'C11',
            'D11',
            'E11',
            'C12',
            'D12',
            'E12',
            '9999',
            '2029-11-10T12:34:56+09:00',
            '3.1415',
            '2029-02-28T03:45:12+00:00',
        ];

        $this->callPrivateMethod(
            $obj,
            'writeSheet',
            [$sheetParts, $data],
        );

        $domDocument = $archive->load(
            $sheet_name,
        );

        $actual = $domDocument->saveXML();

        $ret = true;

        foreach ($expects as $expect) {
            $pos = mb_strpos($actual, $expect);

            if ($pos === false) {
                $ret = false;

                $this->assertEquals(
                    1,
                    0,
                    "not found:{$expect}"
                );
            }
        }

        if ($ret) {
            $this->assertEquals(1, 1);
        }
    }
}
