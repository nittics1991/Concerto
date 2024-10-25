<?php

declare(strict_types=1);

namespace test\Concerto\excel\parts;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\excel\parts\ContentTypes;
use test\Concerto\excel\parts\ExcelPartsHelperTrait;
use Concerto\excel\ExcelArchive;
use Concerto\excel\parts\SharedStrings;

class ContentTypesTest extends ConcertoTestCase
{
    use ExcelPartsHelperTrait;

    #[Test]
    public function addPartName()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'ContentTypes1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new ContentTypes($archive);

        $name = 'MY_FILE_PATH';

        $contenttype = SharedStrings::class;

        $obj->addPartName(
            $name,
            $contenttype,
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

        $obj2 = new ContentTypes($archive2);

        $domDocument = $this->getPrivateProperty(
            $obj2,
            'domDocument',
        );

        $xpath = $this->getPrivateProperty(
            $obj2,
            'xpath',
        );

        $actuals = $xpath->query(
            '//m:Override[@PartName="' . $name . '"]',
        );

        $this->assertEquals(
            1,
            count($actuals),
        );
    }
}
