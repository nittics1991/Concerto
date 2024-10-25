<?php

declare(strict_types=1);

namespace test\Concerto\excel\parts;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\excel\parts\WorkBookParts;
use test\Concerto\excel\parts\ExcelPartsHelperTrait;
use RuntimeException;
use archiveArchive;

class WorkBookPartsTest extends ConcertoTestCase
{
    use ExcelPartsHelperTrait;

    #[Test]
    public function main()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'WorkBookParts1.xlsx';

        $archive = self::createExcelArchive($file);

        $obj = new WorkBookParts($archive);

        $this->assertEquals(
            'rId2',
            $obj->findSheetPartsId('Sheet2'),
        );
    }
}
