<?php

declare(strict_types=1);

namespace test\Concerto\chart;

use test\Concerto\ConcertoTestCase;
use Concerto\sigmagrid\grid\SigmagridColumnInfo;

class SigmagridColumnInfoTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function construct1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $params = [
            'id' => 'cd_tanto',
            'header' => '担当名',
            'fieldName' => 'tanto',
            'fieldIndex' => 3,
            'sortOrder' => 'asc',
            'hidden' => true,
            'exportable' => false,
            'printable' => true,
        ];

        $object = new SigmagridColumnInfo($params);
        $this->assertEquals($params, $object->toArray());
        $this->assertEquals(true, $object->isValid());
    }
}
