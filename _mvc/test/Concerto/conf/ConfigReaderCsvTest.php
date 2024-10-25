<?php

declare(strict_types=1);

namespace test\Concerto\conf;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\conf\ConfigReaderCsv;
use Concerto\standard\ArrayUtil;

class ConfigReaderCsvTest extends ConcertoTestCase
{
    /**
    */
    #[Test]
    public function SuccessFileRead()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $fileName = 'read.csv';
        $file = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $fileName;

        $obj = new ConfigReaderCsv($file);

        $actual = $obj->read();

        $expect = [
            [
                'id' => '1',
                'name' => '青木',
                'height' => '173.2',
                'birthday' => '2011-03-10',

            ],
            [
                'id' => '2',
                'name' => '伊藤',
                'height' => '156.9',
                'birthday' => '2013-11-24',

            ],
            [
                'id' => '3',
                'name' => '上野',
                'height' => '166.5',
                'birthday' => '2009-02-18',

            ],
            [
                'id' => '4',
                'name' => '遠藤',
                'height' => '175.4',
                'birthday' => '2010-01-31',

            ],
            [
                'id' => '5',
                'name' => '岡田',
                'height' => '181.8',
                'birthday' => '2012-08-04',

            ],
        ];

        $this->assertEquals($expect, $actual);
    }
}
