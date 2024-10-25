<?php

declare(strict_types=1);

namespace test\Concerto\excel\parts;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\excel\reader\CellDataReader;
use test\Concerto\excel\parts\ExcelPartsHelperTrait;
use Concerto\excel\parts\ExcelNode;
use DateTimeImmutable;
use DOMDocument;
use DOMXPath;
use RuntimeException;

class CellDataReaderTest extends ConcertoTestCase
{
    use ExcelPartsHelperTrait;

    public static function findBySharedStringProvider()
    {
        return [
            ['1', '伊藤'],
            ['5', "東京都\n府中市"],
        ];
    }

    #[Test]
    #[DataProvider('findBySharedStringProvider')]
    public function findBySharedString(
        string $no,
        string $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'CellDataReader1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new CellDataReader(
            $archive,
        );

        $actual = $this->callPrivateMethod(
            $obj,
            'findBySharedString',
            [$no],
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public static function strToNumberProvider()
    {
        return [
            ['12345', 12345],
            ['-12345', -12345],
            ['+12345', 12345],
            ['12.345', 12.345],
            ['-12.345', -12.345],
            ['+12.345', 12.345],
        ];
    }

    #[Test]
    #[DataProvider('strToNumberProvider')]
    public function strToNumber(
        string $value,
        int|float $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'CellDataReader1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new CellDataReader(
            $archive,
        );

        $actual = $this->callPrivateMethod(
            $obj,
            'strToNumber',
            [$value],
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public static function extractValueNodeProvider()
    {
        /*
            '<row r="12">' .
            '<c r="H12" t="n">' .
            '<v>9999</v>' .
            '</c>' .
            '<c r="AA12" t="d">' .
            '<v>2134-03-27T12:34:56+09:00</v>' .
            '</c>' .
            '<c r="BA12" t="s">' .
            '<v>5</v>' .
            '</c>'.
            '</row>',
        */

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
        $value[2]->text = '5';

        $column[2]->children[0] = $value[2];

        $node->children = $column;

        return [
            [
                $column[0],
                '9999',
            ],
            [
                $column[1],
                '2134-03-27T12:34:56+09:00',
            ],
            [
                $column[2],
                '5',
            ],
        ];
    }

    #[Test]
    #[DataProvider('extractValueNodeProvider')]
    public function extractValueNode(
        ExcelNode $cell,
        string $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'CellDataReader1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new CellDataReader(
            $archive,
        );

        $actual = $this->callPrivateMethod(
            $obj,
            'extractValueNode',
            [$cell],
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public static function cellNodeToDataProvider()
    {
        /*
            '<row r="12">' .
            '<c r="H12" t="n">' .
            '<v>9999</v>' .
            '</c>' .
            '<c r="AA12" t="d">' .
            '<v>2134-03-27T12:34:56+09:00</v>' .
            '</c>' .
            '<c r="BA12" t="s">' .
            '<v>5</v>' .
            '</c>'.
            '</row>',
        */

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
        $value[2]->text = '5';

        $column[2]->children[0] = $value[2];

        $node->children = $column;

        return [
            [
                $column[0],
                9999,
            ],
            [
                $column[1],
                new DateTimeImmutable(
                    '2134-03-27T12:34:56+09:00',
                ),
            ],
            [
                $column[2],
                "東京都\n府中市",
            ],
        ];
    }

    #[Test]
    #[DataProvider('cellNodeToDataProvider')]
    public function cellNodeToData(
        ExcelNode $cell,
        mixed $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'CellDataReader1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new CellDataReader(
            $archive,
        );

        $actual = $this->callPrivateMethod(
            $obj,
            'cellNodeToData',
            [$cell],
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public static function rowNodeToDataProvider()
    {
        /*
            '<row r="12">' .
            '<c r="H12" t="n">' .
            '<v>9999</v>' .
            '</c>' .
            '<c r="AA12" t="d">' .
            '<v>2134-03-27T12:34:56+09:00</v>' .
            '</c>' .
            '<c r="BA12" t="s">' .
            '<v>5</v>' .
            '</c>'.
            '</row>',
        */

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
        $value[2]->text = '5';

        $column[2]->children[0] = $value[2];

        $node->children = $column;

        $expext[12][8] = 9999;

        $expext[12][27] = new DateTimeImmutable(
            '2134-03-27T12:34:56+09:00',
        );

        $expext[12][53] = "東京都\n府中市";

        return [
            [
                $node,
                $expext,
            ],
        ];
    }

    #[Test]
    #[DataProvider('rowNodeToDataProvider')]
    public function rowNodeToData(
        ExcelNode $row,
        mixed $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'CellDataReader1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new CellDataReader(
            $archive,
        );

        $actual = $this->callPrivateMethod(
            $obj,
            'rowNodeToData',
            [$row],
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }
}
