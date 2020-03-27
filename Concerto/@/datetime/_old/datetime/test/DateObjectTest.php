<?php

namespace Concerto\test\domain\datetime;

use Concerto\test\ConcertoTestCase;
use Concerto\datetime\DateObject;

class DateObjectTest extends ConcertoTestCase
{
    /**
    *   @test
    **/
    public function basic()
    {
//      $this->markTestIncomplete();
        
        $object = new DateObject();
        $today = date('Ymd 000000');
        $this->assertEquals(strtotime($today), $object->getTimestamp());
        
        $object = new DateObject('20161230 123456');
        $expect = strtotime('2016-12-30 000000');
        $this->assertEquals($expect, $object->getTimestamp());
        
        $this->assertEquals('20161230', $object->toString());
    }
}
