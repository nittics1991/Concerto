<?php

declare(strict_types=1);

namespace Concerto\test\chart;

use Concerto\test\ConcertoTestCase;
use Concerto\sigmagrid\grid\SigmagridColumnInfo;

class SigmagridColumnInfoTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function construct1()
    {
//      $this->markTestIncomplete();

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
