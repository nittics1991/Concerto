<?php

declare(strict_types=1);

namespace test\Concerto\excel;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\excel\ExcelProcessCleaner;
use Concerto\win\Win32Process;
use RuntimeException;

class ExcelProcessCleanerTest extends ConcertoTestCase
{
    protected function setUp(): void
    {
        if (
            !isset($_SERVER["OS"]) ||
            stripos($_SERVER["OS"], 'WINDOWS') === false
        ) {
            $this->markTestSkipped('Windows上でのみテスト実行');
            return;
        }
    }

    /**
    */
    #[Test]
    public function execute1()
    {
        $this->markTestIncomplete('--- ExcelProcessCleaner is manual test ---');

        $processes = (new Win32Process())
            ->findByName('EXCEL.EXE');

        $obj = new ExcelProcessCleaner(
            new \DateInterval('PT1S')
        );
        $actual = $obj();
        $this->assertEquals(true, count($processes) == count($actual));
    }
}
