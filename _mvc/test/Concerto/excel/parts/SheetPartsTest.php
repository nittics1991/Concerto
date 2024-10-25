<?php

declare(strict_types=1);

namespace test\Concerto\excel\parts;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\excel\parts\SheetParts;
use test\Concerto\excel\parts\ExcelPartsHelperTrait;
use DOMDocument;
use DOMXPath;
use Concerto\excel\ExcelArchive;
use Concerto\excel\parts\ExcelNode;

class SheetPartsTest extends ConcertoTestCase
{
    use ExcelPartsHelperTrait;

    public static function addSheetDataProvider()
    {
        $rows[0] = new ExcelNode();
        $rows[0]->name = 'row';
        $rows[0]->attribute = [
            'r' => '2',
        ];

        $cell[0] = new ExcelNode();
        $cell[0]->name = 'c';
        $cell[0]->attribute = [
            'r' => 'C2',
            't' => 'n',
        ];

        $value[0] = new ExcelNode();
        $value[0]->name = 'v';
        $value[0]->text = '123';

        $cell[0]->children[0] = $value[0];

        $cell[1] = new ExcelNode();
        $cell[1]->name = 'c';
        $cell[1]->attribute = [
            'r' => 'D2',
            't' => 'inlineStr',
        ];

        $instr[0] = new ExcelNode();
        $instr[0]->name = 'si';

        $text[0] = new ExcelNode();
        $text[0]->name = 't';
        $text[0]->text = '文字列です';

        $instr[0]->children[0] = $text[0];

        $cell[1]->children[0] = $instr[0];

        $rows[0]->children = $cell;

        return [
            [
                $rows,
                '<row r="2">' .
                    '<c r="C2" t="n">' .
                    '<v>123</v>' .
                    '</c>' .
                    '<c r="D2" t="inlineStr">' .
                    '<si>' .
                    '<t>文字列です</t>' .
                    '</si>' .
                    '</c>' .
                    '</row>',
            ],
        ];
    }

    #[Test]
    #[DataProvider('addSheetDataProvider')]
    public function addSheetData(
        $excelNodes,
        $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'SheetParts1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new SheetParts(
            $archive,
            'xl/worksheets/sheet1.xml',
        );

        $obj->addSheetData($excelNodes);

        $archive->close();

        $save_file = $archive->filepath();

        $archive2 = new ExcelArchive($save_file);

        $domDocument = $archive2->load(
            'xl/worksheets/sheet1.xml',
        );

        $xml = $domDocument->saveXML();

        $extracted1 = mb_ereg_replace(
            '^<?.+<sheetData>',
            '',
            $xml,
        );

        $actual = mb_ereg_replace(
            '</sheetData>.+$',
            '',
            $extracted1,
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    #[Test]
    public function loadData()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'SheetParts1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new SheetParts(
            $archive,
            'xl/worksheets/sheet2.xml',
        );

        $actual = $obj->loadData();

        //row r="5"-----------------------------------------

        //<row r="5" spans="3:5" x14ac:dyDescent="0.25">
        $row[0] = new ExcelNode();
        $row[0]->name = 'row';
        $row[0]->attribute = [
            "r" => "5",
            "spans" => "3:5",
            "x14ac:dyDescent" => "0.25",
        ];

        // <c r="C5" t="s">
        $cell[0][0] = new ExcelNode();
        $cell[0][0]->name = 'c';
        $cell[0][0]->attribute = [
            "r" => "C5",
            "t" => "s",
        ];

        //<v>0</v>
        $value[0][0] = new ExcelNode();
        $value[0][0]->name = 'v';
        $value[0][0]->text = '0';

        $cell[0][0]->children[0] = $value[0][0];

        //<c r="D5">
        $cell[0][1] = new ExcelNode();
        $cell[0][1]->name = 'c';
        $cell[0][1]->attribute = [
            "r" => "D5",
        ];

        //<v>11</v>
        $value[0][1] = new ExcelNode();
        $value[0][1]->name = 'v';
        $value[0][1]->text = '11';

        $cell[0][1]->children[0] = $value[0][1];

        //<c r="E5">
        $cell[0][2] = new ExcelNode();
        $cell[0][2]->name = 'c';
        $cell[0][2]->attribute = [
            "r" => "E5",
        ];

        //<v>111.11</v>
        $value[0][2] = new ExcelNode();
        $value[0][2]->name = 'v';
        $value[0][2]->text = '111.11';

        $cell[0][2]->children[0] = $value[0][2];

        $row[0]->children = $cell[0];

        //row r="6"-----------------------------------------

        //<row r="6" spans="3:5" x14ac:dyDescent="0.25">
        $row[1] = new ExcelNode();
        $row[1]->name = 'row';
        $row[1]->attribute = [
            "r" => "6",
            "spans" => "3:5",
            "x14ac:dyDescent" => "0.25",
        ];

        //<c r="C6" t="s">
        $cell[1][0] = new ExcelNode();
        $cell[1][0]->name = 'c';
        $cell[1][0]->attribute = [
            "r" => "C6",
            "t" => "s",
        ];

        //<v>1</v>
        $value[1][0] = new ExcelNode();
        $value[1][0]->name = 'v';
        $value[1][0]->text = '1';

        $cell[1][0]->children[0] = $value[1][0];

        //<c r="D6">
        $cell[1][1] = new ExcelNode();
        $cell[1][1]->name = 'c';
        $cell[1][1]->attribute = [
            "r" => "D6",
        ];

        //<v>12</v>
        $value[1][1] = new ExcelNode();
        $value[1][1]->name = 'v';
        $value[1][1]->text = '12';

        $cell[1][1]->children[0] = $value[1][1];

        //<c r="E6">
        $cell[1][2] = new ExcelNode();
        $cell[1][2]->name = 'c';
        $cell[1][2]->attribute = [
            "r" => "E6",
        ];

        //<v>222.22</v>
        $value[1][2] = new ExcelNode();
        $value[1][2]->name = 'v';
        $value[1][2]->text = '222.22';

        $cell[1][2]->children[0] = $value[1][2];

        $row[1]->children = $cell[1];

        //row r="7"-----------------------------------------

        //<row r="7" spans="3:5" x14ac:dyDescent="0.25">
        $row[2] = new ExcelNode();
        $row[2]->name = 'row';
        $row[2]->attribute = [
            "r" => "7",
            "spans" => "3:5",
            "x14ac:dyDescent" => "0.25",
        ];

        //<c r="C7" t="s">
        $cell[2][0] = new ExcelNode();
        $cell[2][0]->name = 'c';
        $cell[2][0]->attribute = [
            "r" => "C7",
            "t" => "s",
        ];

        //<v>2</v>
        $value[2][0] = new ExcelNode();
        $value[2][0]->name = 'v';
        $value[2][0]->text = '2';

        $cell[2][0]->children[0] = $value[2][0];

        //<c r="D7">
        $cell[2][1] = new ExcelNode();
        $cell[2][1]->name = 'c';
        $cell[2][1]->attribute = [
            "r" => "D7",
        ];

        //<v>13</v>
        $value[2][1] = new ExcelNode();
        $value[2][1]->name = 'v';
        $value[2][1]->text = '13';

        $cell[2][1]->children[0] = $value[2][1];

        //<c r="E7">
        $cell[2][2] = new ExcelNode();
        $cell[2][2]->name = 'c';
        $cell[2][2]->attribute = [
            "r" => "E7",
        ];

        //<v>333.33</v>
        $value[2][2] = new ExcelNode();
        $value[2][2]->name = 'v';
        $value[2][2]->text = '333.33';

        $cell[2][2]->children[0] = $value[2][2];

        $row[2]->children = $cell[2];

        //row r="8"-----------------------------------------

        //<row r="8" spans="3:5" x14ac:dyDescent="0.25">
        $row[3] = new ExcelNode();
        $row[3]->name = 'row';
        $row[3]->attribute = [
            "r" => "8",
            "spans" => "3:5",
            "x14ac:dyDescent" => "0.25",
        ];

        //<c r="C8" t="s">
        $cell[3][0] = new ExcelNode();
        $cell[3][0]->name = 'c';
        $cell[3][0]->attribute = [
            "r" => "C8",
            "t" => "s",
        ];

        //<v>3</v>
        $value[3][0] = new ExcelNode();
        $value[3][0]->name = 'v';
        $value[3][0]->text = '3';

        $cell[3][0]->children[0] = $value[3][0];

        //<c r="D8">
        $cell[3][1] = new ExcelNode();
        $cell[3][1]->name = 'c';
        $cell[3][1]->attribute = [
            "r" => "D8",
        ];

        //<v>14</v>
        $value[3][1] = new ExcelNode();
        $value[3][1]->name = 'v';
        $value[3][1]->text = '14';

        $cell[3][1]->children[0] = $value[3][1];

        //<c r="E8">
        $cell[3][2] = new ExcelNode();
        $cell[3][2]->name = 'c';
        $cell[3][2]->attribute = [
            "r" => "E8",
        ];

        //<v>444.44</v>
        $value[3][2] = new ExcelNode();
        $value[3][2]->name = 'v';
        $value[3][2]->text = '444.44';

        $cell[3][2]->children[0] = $value[3][2];

        $row[3]->children = $cell[3];

        $expect = $row;

        $this->assertEquals(
            $expect,
            $actual,
        );
    }
}
