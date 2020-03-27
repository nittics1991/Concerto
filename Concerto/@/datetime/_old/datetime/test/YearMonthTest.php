<?php

namespace Concerto\test\domain\datetime;

use Concerto\test\ConcertoTestCase;
use Concerto\datetime\YearMonth;

class YearMonthTest extends ConcertoTestCase
{
    /**
    *   @test
    **/
    public function basic()
    {
//      $this->markTestIncomplete();
        
        $object = new YearMonth();
        $expect = strtotime(date('Ym01 000000'));
        $this->assertEquals($expect, $object->getTimestamp());
        
        $object = new YearMonth('201612');
        $expect = strtotime('2016-12-01');
        $this->assertEquals($expect, $object->getTimestamp());
        $this->assertEquals('201612', $object->toString());
        
        $object = new YearMonth('2016-05-11');
        $expect = strtotime('2016-05-01');
        $this->assertEquals($expect, $object->getTimestamp());
        $this->assertEquals('201605', $object->toString());
    }
}
