<?php

declare(strict_types=1);

namespace Concerto\test\chart;

use Concerto\test\ConcertoTestCase;
use Concerto\sigmagrid\grid\SigmagridPageInfo;

class SigmagridPageInfoTest extends ConcertoTestCase
{
    /**
    *   @test
    **/
    public function construct1()
    {
//      $this->markTestIncomplete();
        
        $params = [
            'pageSize' => 11,
            'pageNum' => 3,
            'totalRowNum' => 100,
            'totalPageNum' => 10,
            'startRowNum' => 11,
            'endRowNum' => 20,
        ];
        
        $object = new SigmagridPageInfo($params);
        $this->assertEquals($params, $object->toArray());
        $this->assertEquals(true, $object->isValid());
    }
}
