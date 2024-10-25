<?php

declare(strict_types=1);

namespace test\Concerto\sigmagrid;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\sigmagrid\grid\SigmagridColumnInfo;
use Concerto\sigmagrid\grid\SigmagridColumnInfos;

class SigmagridColumnInfosTest extends ConcertoTestCase
{
    /**
    */
    #[Test]
    public function construct1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $params = [
            [
                'id' => 'cd_tanto',
                'header' => '担当名',
                'fieldName' => 'tanto',
                'fieldIndex' => 3,
                'sortOrder' => 'asc',
                'hidden' => true,
                'exportable' => false,
                'printable' => true,
            ],
            [
                'id' => 'cd_tanto2',
                'header' => '担当名2',
                'fieldName' => 'tanto2',
                'fieldIndex' => 6,
                'sortOrder' => 'desc',
                'hidden' => false,
                'exportable' => true,
                'printable' => false,
            ],
        ];

        $object = new SigmagridColumnInfos($params);
        $this->assertEquals(true, $object->isValid());

        $i = 0;
        foreach ($object as $obj) {
            $this->assertEquals($params[$i], $obj->toArray());
            $i++;
        }
    }

    /**
    */
    #[Test]
    public function valid1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $params = [
            [
                'id' => 'cd_tanto',
                'header' => '担当名',
                'fieldName' => 'tanto',
                'fieldIndex' => 3,
                'sortOrder' => 'asc',
                'hidden' => true,
                'exportable' => false,
                'printable' => true,
            ],
            [
                'id' => 'cd_tanto2',
                'header' => '担当名2',
                'fieldName' => 'tanto2',
                'fieldIndex' => 6,
                'sortOrder' => 'ZZZZZ',
                'hidden' => false,
                'exportable' => true,
                'printable' => 12,
            ],
        ];

        $expect = [
            1 => [
                'sortOrder' => [''],
                'printable' => [''],
            ]
        ];

        $object = new SigmagridColumnInfos($params);
        $this->assertEquals(false, $object->isValid());
        $this->assertEquals($expect, $object->getValidError());
    }
}
