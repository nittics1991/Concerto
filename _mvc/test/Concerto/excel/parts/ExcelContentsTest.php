<?php

declare(strict_types=1);

namespace test\Concerto\excel\parts;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\excel\parts\ExcelContents;
use test\Concerto\excel\parts\ExcelPartsHelperTrait;
use RuntimeException;
use Concerto\excel\parts\{
    SheetParts,
    SharedStrings,
};

class ExcelContentsTest extends ConcertoTestCase
{
    use ExcelPartsHelperTrait;

    #[Test]
    public function findSheetPartsId()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'ExcelContents1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new ExcelContents($archive);

        $this->assertEquals(
            'rId2',
            $this->callPrivateMethod(
                $obj,
                'findSheetPartsId',
                ['Sheet2'],
            ),
        );
    }

    #[Test]
    public function findSheetFileName()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'ExcelContents1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new ExcelContents($archive);

        $this->assertEquals(
            'xl/worksheets/sheet2.xml',
            $this->callPrivateMethod(
                $obj,
                'findSheetFileName',
                ['rId2'],
            ),
        );
    }

    #[Test]
    public function getSheetParts()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'ExcelContents1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new ExcelContents($archive);

        $sheetParts = $obj->getSheetParts(
            'Sheet2',
        );

        $this->assertEquals(
            true,
            $sheetParts instanceof SheetParts,
        );
    }

    #[Test]
    public function getSharedStrings()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'ExcelContents1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new ExcelContents($archive);

        $sheetParts = $obj->getSharedStrings();

        $this->assertEquals(
            true,
            $sheetParts instanceof SharedStrings,
        );
    }
}
