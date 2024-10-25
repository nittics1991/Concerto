<?php

declare(strict_types=1);

namespace test\Concerto\excel\parts;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\excel\parts\OfficeParts;
use test\Concerto\excel\parts\ExcelPartsHelperTrait;
use DOMDocument;
use Concerto\excel\parts\ExcelNode;

////////////////////////////////////////////////////////////
class OfficePartsTestClass1 extends OfficeParts
{
    protected string $file_path = 'xl/workbook.xml';

    protected string $namespace = 'fake';
}

class OfficePartsTestClass2 extends OfficeParts
{
    protected string $file_path = 'xl/worksheets/sheet1.xml';

    protected string $namespace =
        'http://schemas.openxmlformats.org/spreadsheetml/2006/main';
}

////////////////////////////////////////////////////////////

class OfficePartsTest extends ConcertoTestCase
{
    use ExcelPartsHelperTrait;

    public static function excelNodeToXmlProvider()
    {
        //要素のみ
        $parent[0][0] = new ExcelNode();
        $parent[0][0]->name = 'row';

        //テキスト
        $parent[1][0] = new ExcelNode();
        $parent[1][0]->name = 'row';
        $parent[1][0]->text = 'データ';

        //属性
        $parent[2][0] = new ExcelNode();
        $parent[2][0]->name = 'row';
        $parent[2][0]->text = 'データ';
        $parent[2][0]->attribute = [
            'a' => 'ABC',
            'b' => '123',
        ];

        //子要素
        $parent[3][0] = new ExcelNode();
        $parent[3][0]->name = 'row';

        $child[3][0] = new ExcelNode();
        $child[3][0]->name = 'c';
        $child[3][0]->text = '子要素1';
        $child[3][0]->attribute = [
            'a' => 'ABC',
            'b' => '123',
        ];

        $child[3][1] = new ExcelNode();
        $child[3][1]->name = 'si';
        $child[3][1]->text = '子要素2';
        $child[3][1]->attribute = [
            'd' => 'DEF',
            'e' => '456',
        ];

        $parent[3][0]->children = $child[3];

        //子孫要素
        $parent[4][0] = new ExcelNode();
        $parent[4][0]->name = 'row';

        $child[4][0] = new ExcelNode();
        $child[4][0]->name = 'c';
        $child[4][0]->attribute = [
            'a' => 'ABC',
            'b' => '123',
        ];

        $child[4][1] = new ExcelNode();
        $child[4][1]->name = 'si';
        $child[4][1]->attribute = [
            'd' => 'DEF',
            'e' => '456',
        ];

        $descendants[4][0][0] = new ExcelNode();
        $descendants[4][0][0]->name = 'v';
        $descendants[4][0][0]->text = '孫1';

        $descendants[4][0][1] = new ExcelNode();
        $descendants[4][0][1]->name = 'w';
        $descendants[4][0][1]->text = '孫2';

        $child[4][0]->children = $descendants[4][0];

        $descendants[4][1][0] = new ExcelNode();
        $descendants[4][1][0]->name = 'v';
        $descendants[4][1][0]->text = '孫11';

        $descendants[4][1][1] = new ExcelNode();
        $descendants[4][1][1]->name = 'w';
        $descendants[4][1][1]->text = '孫12';

        $child[4][1]->children = $descendants[4][1];

        $parent[4][0]->children = $child[4];

        return [
            [
                $parent[0],
                '<row/>',
            ],
            [
                $parent[1],
                '<row>データ</row>',
            ],
            [
                $parent[2],
                '<row a="ABC" b="123">データ</row>',
            ],
            [
                $parent[3],
                '<row>' .
                    '<c a="ABC" b="123">子要素1</c>' .
                    '<si d="DEF" e="456">子要素2</si>' .
                    '</row>',
            ],
            [
                $parent[4],
                '<row>' .
                    '<c a="ABC" b="123">' .
                    '<v>孫1</v>' .
                    '<w>孫2</w>' .
                    '</c>' .
                    '<si d="DEF" e="456">' .
                    '<v>孫11</v>' .
                    '<w>孫12</w>' .
                    '</si>' .
                    '</row>',
            ],
        ];
    }

    #[Test]
    #[DataProvider('excelNodeToXmlProvider')]
    public function excelNodeToXml(
        array $excelNodes,
        string $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'empty_book.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new OfficePartsTestClass1($archive);

        $actual = $this->callPrivateMethod(
            $obj,
            'excelNodeToXml',
            [$excelNodes],
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public static function domstringToFragmentProvider()
    {
        $dom_string[0] =
            '<groups>' .
            '<parent a="123">' .
            '<child>AAA</child>' .
            '<child>BBB</child>' .
            '</parent>' .
            '<parent a="123">' .
            '<child>aaa</child>' .
            '<child>bbb</child>' .
            '</parent>' .
            '</groups>';

        return [
            [$dom_string[0]],
        ];
    }

    #[Test]
    #[DataProvider('domstringToFragmentProvider')]
    public function domstringToFragment(
        string $dom_string,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'empty_book.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new OfficePartsTestClass1($archive);

        $fragment = $this->callPrivateMethod(
            $obj,
            'domstringToFragment',
            [$dom_string],
        );

        $domDocument = $this->getPrivateProperty(
            $obj,
            'domDocument',
        );

        $entries = $domDocument->getElementsByTagName('workbook');

        $entries->item(0)->appendChild($fragment);

        $this->assertNotEquals(
            false,
            mb_strpos(
                $domDocument->saveXML(),
                $dom_string,
            ),
        );
    }

    #[Test]
    public function queryXml()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'OfficeParts1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new OfficePartsTestClass2($archive);

        $element = $this->callPrivateMethod(
            $obj,
            'queryXml',
            ['//m:sheetData'],
        );

        $this->assertEquals(
            'sheetData',
            $element->tagName,
        );
    }

    public static function domNamedNodeMapToArrayProvider()
    {
        return [
            [
                '<root row="12" column="C" class="main"/>',
                ["row" => "12", "column" => "C", "class" => "main"],
            ],
        ];
    }

    #[Test]
    #[DataProvider('domNamedNodeMapToArrayProvider')]
    public function domNamedNodeMapToArray(
        string $xml,
        array $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'empty_book.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new OfficePartsTestClass1($archive);

        $domDocument = new DOMDocument();

        $domDocument->loadXML($xml);

        $root = $domDocument->firstChild;

        $attributes = $root->attributes;

        $actual = $this->callPrivateMethod(
            $obj,
            'domNamedNodeMapToArray',
            [$attributes],
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public static function xmlToExcelNodesProvider()
    {
        //要素のみ
        $parent[0][0] = new ExcelNode();
        $parent[0][0]->name = 'row';

        //テキスト
        $parent[1][0] = new ExcelNode();
        $parent[1][0]->name = 'row';
        $parent[1][0]->text = 'データ';

        //属性
        $parent[2][0] = new ExcelNode();
        $parent[2][0]->name = 'row';
        $parent[2][0]->text = 'データ';
        $parent[2][0]->attribute = [
            'a' => 'ABC',
            'b' => '123',
        ];

        //子要素
        $parent[3][0] = new ExcelNode();
        $parent[3][0]->name = 'row';

        $child[3][0] = new ExcelNode();
        $child[3][0]->name = 'c';
        $child[3][0]->text = '子要素1';
        $child[3][0]->attribute = [
            'a' => 'ABC',
            'b' => '123',
        ];

        $child[3][1] = new ExcelNode();
        $child[3][1]->name = 'si';
        $child[3][1]->text = '子要素2';
        $child[3][1]->attribute = [
            'd' => 'DEF',
            'e' => '456',
        ];

        $parent[3][0]->children = $child[3];

        //子孫要素
        $parent[4][0] = new ExcelNode();
        $parent[4][0]->name = 'row';

        $child[4][0] = new ExcelNode();
        $child[4][0]->name = 'c';
        $child[4][0]->attribute = [
            'a' => 'ABC',
            'b' => '123',
        ];

        $child[4][1] = new ExcelNode();
        $child[4][1]->name = 'si';
        $child[4][1]->attribute = [
            'd' => 'DEF',
            'e' => '456',
        ];

        $descendants[4][0][0] = new ExcelNode();
        $descendants[4][0][0]->name = 'v';
        $descendants[4][0][0]->text = '孫1';

        $descendants[4][0][1] = new ExcelNode();
        $descendants[4][0][1]->name = 'w';
        $descendants[4][0][1]->text = '孫2';

        $child[4][0]->children = $descendants[4][0];

        $descendants[4][1][0] = new ExcelNode();
        $descendants[4][1][0]->name = 'v';
        $descendants[4][1][0]->text = '孫11';

        $descendants[4][1][1] = new ExcelNode();
        $descendants[4][1][1]->name = 'w';
        $descendants[4][1][1]->text = '孫12';

        $child[4][1]->children = $descendants[4][1];

        $parent[4][0]->children = $child[4];

        return [
            [
                '<row/>',
                $parent[0],
            ],
            [
                '<row>データ</row>',
                $parent[1],
            ],
            [
                '<row a="ABC" b="123">データ</row>',
                $parent[2],
            ],
            [
                '<row>' .
                    '<c a="ABC" b="123">子要素1</c>' .
                    '<si d="DEF" e="456">子要素2</si>' .
                    '</row>',
                $parent[3],
            ],
            [
                '<row>' .
                    '<c a="ABC" b="123">' .
                    '<v>孫1</v>' .
                    '<w>孫2</w>' .
                    '</c>' .
                    '<si d="DEF" e="456">' .
                    '<v>孫11</v>' .
                    '<w>孫12</w>' .
                    '</si>' .
                    '</row>',
                $parent[4],
            ],
        ];
    }

    #[Test]
    #[DataProvider('xmlToExcelNodesProvider')]
    public function xmlToExcelNodes(
        string $xml,
        array $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'empty_book.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new OfficePartsTestClass1($archive);

        $domDocument = new DOMDocument();

        $domDocument->loadXML($xml);

        $children = $domDocument->childNodes;

        $actual = $this->callPrivateMethod(
            $obj,
            'xmlToExcelNodes',
            [$children],
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }
}
