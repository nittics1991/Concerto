<?php

namespace Concerto\test\domain\datetime;

use Concerto\test\ConcertoTestCase;
use Concerto\datetime\FiscalYear;

class FiscalYearTest extends ConcertoTestCase
{
    /**
    *   @test
    **/
    public function basic()
    {
//      $this->markTestIncomplete();
        
        $object = new FiscalYear('2016K');
        $expect = strtotime('2016-04-01 000000');
        $this->assertEquals($expect, $object->getTimestamp());
        $this->assertEquals('2016K', $object->toString());
        
        $object = new FiscalYear('2017S');
        $expect = strtotime('2017-10-01 000000');
        $this->assertEquals($expect, $object->getTimestamp());
        $this->assertEquals('2017S', $object->toString());
        
        $object = new FiscalYear('2017-12-22');
        $expect = strtotime('2017-10-01 000000');
        $this->assertEquals($expect, $object->getTimestamp());
        $this->assertEquals('2017S', $object->toString());
    }
}
