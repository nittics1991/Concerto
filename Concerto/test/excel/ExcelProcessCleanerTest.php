<?php

declare(strict_types=1);

namespace Concerto\test\excel;

use Concerto\test\ConcertoTestCase;
use Concerto\excel\ExcelProcessCleaner;
use Concerto\win\Win32Process;
use RuntimeException;

class ExcelProcessCleanerTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function execute1()
    {
//      $this->markTestIncomplete();
        
        $processes = (new Win32Process())
            ->findByName('EXCEL.EXE');
        
        $obj = new ExcelProcessCleaner(
            new \DateInterval('PT1S')
        );
        $actual = $obj();
        $this->assertEquals(true, count($processes) == count($actual));
    }
}
