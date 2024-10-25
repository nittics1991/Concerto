<?php

declare(strict_types=1);

namespace test\Concerto\excel;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\excel\ExcelTemplateWriter;

class ExcelTemplateWriterTest extends ConcertoTestCase
{
    #[Test]
    public function createWorkDir()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new ExcelTemplateWriter();

        $temp_dir = $this->getPrivateProperty(
            $obj,
            'temp_dir',
        );

        $this->assertEquals(
            true,
            file_exists($temp_dir)
        );
    }

    #[Test]
    public function openAndSave()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file_name = realpath(
            __DIR__ .
            DIRECTORY_SEPARATOR .
            'data/ExcelTemplateWriter1.xlsx'
        );

        $obj = new ExcelTemplateWriter();

        $save_file = sys_get_temp_dir() .
            DIRECTORY_SEPARATOR .
            uniqid() .
            DIRECTORY_SEPARATOR .
            uniqid() . '.xlsx';

        mkdir(dirname($save_file));

        $obj->open($file_name)
            ->save($save_file);

        $this->assertEquals(
            true,
            file_exists($save_file)
        );
    }
}
