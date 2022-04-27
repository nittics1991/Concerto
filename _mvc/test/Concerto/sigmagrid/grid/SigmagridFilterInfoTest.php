<?php

declare(strict_types=1);

namespace test\Concerto\chart;

use test\Concerto\ConcertoTestCase;
use Concerto\sigmagrid\grid\SigmagridFilterInfo;

class SigmagridFilterInfoTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function construct1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $params = [
            'fieldName' => 'cd_tanto',
            'value' => 12,
            'logic' => 'lessEqual',
        ];

        $object = new SigmagridFilterInfo($params);
        $this->assertEquals($params, $object->toArray());
        $this->assertEquals(true, $object->isValid());
    }
}
