<?php

declare(strict_types=1);

namespace test\Concerto\excel\parts;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\excel\parts\SharedStrings;
use test\Concerto\excel\parts\ExcelPartsHelperTrait;
use RuntimeException;
use Concerto\excel\ExcelArchive;

class SharedStringsTest extends ConcertoTestCase
{
    use ExcelPartsHelperTrait;

    #[Test]
    public function readAllString()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'SharedStrings1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new SharedStrings($archive);

        $this->assertEquals(
            9,
            $this->getPrivateProperty(
                $obj,
                'last_position',
            ),
        );
    }

    #[Test]
    public function readAllString2()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'empty_book.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new SharedStrings($archive);

        $this->assertEquals(
            null,
            $this->getPrivateProperty(
                $obj,
                'last_position',
            ),
        );
    }

    public static function stringNoProvider()
    {
        return [
            ['遠藤', 3],
            ["東京都\n府中市", 5],
        ];
    }

    #[Test]
    #[DataProvider('stringNoProvider')]
    public function stringNo(
        string $value,
        int $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'SharedStrings1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new SharedStrings($archive);

        $this->assertEquals(
            $expect,
            $obj->stringNo($value),
        );
    }

    public static function hasStringProvider()
    {
        return [
            ['遠藤', true],
            ["東京都\n府中市", true],
            ["東京都府中市", false],
        ];
    }

    #[Test]
    #[DataProvider('hasStringProvider')]
    public function hasString(
        string $value,
        bool $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'SharedStrings1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new SharedStrings($archive);

        $this->assertEquals(
            $expect,
            $obj->hasString($value),
        );
    }

    public static function addStringProvider()
    {
        return [
            ['遠藤', false, 3],
            ['new string1', true, 10],
            ["東京都\n新宿区", true, 10],
        ];
    }

    #[Test]
    #[DataProvider('addStringProvider')]
    public function addString(
        string $value,
        bool $is_add,
        int $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'SharedStrings1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new SharedStrings($archive);

        $obj->addString($value);

        $add_strings = $this->getPrivateProperty(
            $obj,
            'add_strings',
        );

        $this->assertEquals(
            $is_add,
            in_array($value, $add_strings, true),
        );

        $this->assertEquals(
            $expect,
            $obj->stringNo($value),
        );
    }

    #[Test]
    public function addString2()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'SharedStrings1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new SharedStrings($archive);

        $last_position = $this->getPrivateProperty(
            $obj,
            'last_position',
        );

        $value = '追加1';

        $obj->addString($value);

        $this->assertEquals(
            ++$last_position,
            $obj->stringNo($value),
        );

        $value = '追加2';

        $obj->addString($value);

        $this->assertEquals(
            ++$last_position,
            $obj->stringNo($value),
        );
    }

    #[Test]
    public function dataToXml()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'SharedStrings1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new SharedStrings($archive);

        $obj->addString('追加1');
        $obj->addString('追加2');
        $obj->addString('追加3');

        $xml = $this->callPrivateMethod(
            $obj,
            'dataToXml',
            [],
        );

        $expects = [
            '<si><t xml:space="preserve">追加1</t></si>',
            '<si><t xml:space="preserve">追加2</t></si>',
            '<si><t xml:space="preserve">追加3</t></si>',
        ];

        foreach ($expects as $expect) {
            $this->assertEquals(
                true,
                is_int(mb_strpos($xml, $expect)),
                "undefined:{$expect}",
            );
        }
    }

    #[Test]
    public function close1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'SharedStrings1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new SharedStrings($archive);

        $obj->addString('追加1');
        $obj->addString('追加2');
        $obj->addString('追加3');

        $obj->close();

        $archive->close();

        $saved_file = $archive->filepath();

        $archive2 = new ExcelArchive($saved_file);

        $domDocument = $archive2->load('xl/sharedStrings.xml');

        $xml = $domDocument->saveXML();

        $expects = [
            '<si><t xml:space="preserve">追加1</t></si>',
            '<si><t xml:space="preserve">追加2</t></si>',
            '<si><t xml:space="preserve">追加3</t></si>',
        ];

        foreach ($expects as $expect) {
            $this->assertNotEquals(
                false,
                mb_strpos($xml, $expect),
                "undefined:{$expect}",
            );
        }
    }

    #[Test]
    public function close2()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'empty_book.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new SharedStrings($archive);

        $obj->addString('追加1');
        $obj->addString('追加2');
        $obj->addString('追加3');

        $obj->close();

        $archive->close();

        $saved_file = $archive->filepath();

        $archive2 = new ExcelArchive($saved_file);

        $domDocument = $archive2->load('xl/sharedStrings.xml');

        $xml = $domDocument->saveXML();

        $expects = [
            '<si><t xml:space="preserve">追加1</t></si>',
            '<si><t xml:space="preserve">追加2</t></si>',
            '<si><t xml:space="preserve">追加3</t></si>',
        ];

        foreach ($expects as $expect) {
            $this->assertNotEquals(
                false,
                mb_strpos($xml, $expect),
                "undefined:{$expect}",
            );
        }
    }

    public static function findBySharedStringProvider()
    {
        return [
            [
                0,
                '青木',
            ],
            [
                8,
                "千葉県\n木更津市",
            ],
            [
                9,
                "茨城県\n水戸市",
            ],
        ];
    }

    #[Test]
    #[DataProvider('findBySharedStringProvider')]
    public function findByString(
        int $no,
        string $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'SharedStrings1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new SharedStrings($archive);

        $this->assertEquals(
            $expect,
            $obj->findBySharedString($no),
        );
    }
}
