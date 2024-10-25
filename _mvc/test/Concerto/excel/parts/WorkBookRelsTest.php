<?php

declare(strict_types=1);

namespace test\Concerto\excel\parts;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\excel\parts\WorkBookRels;
use test\Concerto\excel\parts\ExcelPartsHelperTrait;
use RuntimeException;
use Concerto\excel\ExcelArchive;
use Concerto\excel\parts\SharedStrings;

class WorkBookRelsTest extends ConcertoTestCase
{
    use ExcelPartsHelperTrait;

    #[Test]
    public function main()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'WorkBookRels1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new WorkBookRels($archive);

        $this->assertEquals(
            'worksheets/sheet2.xml',
            $obj->findSheetFileName('rId2'),
        );
    }

    public static function getMaxRelationIdProvider()
    {
        return [
            [
                'WorkBookRels1.xlsx',
                'rId5',
            ],
            [
                'empty_book.xlsx',
                'rId3',
            ],
        ];
    }

    #[Test]
    #[DataProvider('getMaxRelationIdProvider')]
    public function getMaxRelationId(
        string $file,
        string $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $archive = self::createExcelArchive($file);

        $obj = new WorkBookRels($archive);

        $actual = $this->callPrivateMethod(
            $obj,
            'getMaxRelationId',
            [],
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public static function createNewRelationIdProvider()
    {
        return [
            [
                'WorkBookRels1.xlsx',
                'rId6',
            ],
            [
                'empty_book.xlsx',
                'rId4',
            ],
        ];
    }

    #[Test]
    #[DataProvider('createNewRelationIdProvider')]
    public function createNewRelationId(
        string $file,
        string $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $archive = self::createExcelArchive($file);

        $obj = new WorkBookRels($archive);

        $actual = $this->callPrivateMethod(
            $obj,
            'createNewRelationId',
            [],
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }


    #[Test]
    public function addRelationship()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'WorkBookRels1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new WorkBookRels($archive);

        $target = 'MY_FILE_PATH';

        $relation_type = SharedStrings::class;

        $obj->addRelationship(
            $target,
            $relation_type,
        );

        $archive->close();

        $archive_file = $this->getPrivateProperty(
            $archive,
            'excel_path',
        );

        $coped_file = "{$archive_file}2.xlsx";

        copy($archive_file, $coped_file);

        $archive2 = new ExcelArchive(
            $coped_file,
        );

        $obj2 = new WorkBookRels($archive2);

        $domDocument = $this->getPrivateProperty(
            $obj2,
            'domDocument',
        );

        $xpath = $this->getPrivateProperty(
            $obj2,
            'xpath',
        );

        $actuals = $xpath->query(
            '//m:Relationship[@Target="' . $target . '"]',
        );

        $this->assertEquals(
            1,
            count($actuals),
        );
    }
}
