<?php

declare(strict_types=1);

namespace test\Concerto\chart;

use test\Concerto\ConcertoTestCase;
use Concerto\sigmagrid\grid\SigmagridSortInfo;

class SigmagridSortInfoTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function construct1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $params = [
            'columnId' => 'cd_tanto',
            'fieldName' => 'tanto',
            'sortOrder' => 'asc',
            'getSortValue' => 'abc',
            'sortFn' => 'krsort',
        ];

        $object = new SigmagridSortInfo($params);
        $this->assertEquals($params, $object->toArray());
        $this->assertEquals(true, $object->isValid());
    }
}
