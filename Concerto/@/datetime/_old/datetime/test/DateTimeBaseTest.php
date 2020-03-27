<?php

namespace Concerto\test\domain\datetime;

use Concerto\test\ConcertoTestCase;
use Concerto\datetime\DateTimeBase;

class StubDateTimeBase extends DateTimeBase
{
    public function toString()
    {
        return $this->format('Ymd His');
    }
}

////////////////////////////////////////////////////////////

class DateTimeBaseTest extends ConcertoTestCase
{
    /**
    *   @test
    **/
    public function nextMonth()
    {
//      $this->markTestIncomplete();
        
        //add
        $object = new StubDateTimeBase('20170201 120000');
        $this->assertEquals('20170301 120000', $object->nextMonth()->format('Ymd His'));
        
        $object = new StubDateTimeBase('20160229 120000');
        $this->assertEquals('20160229 120000', $object->nextMonth(0)->format('Ymd His'));
        
        $object = new StubDateTimeBase('20170201 120000');
        $this->assertEquals('20170301 120000', $object->nextMonth(1)->format('Ymd His'));
        
        $object = new StubDateTimeBase('20170131 120000');
        $this->assertEquals('20170228 120000', $object->nextMonth(1)->format('Ymd His'));
        
        $object = new StubDateTimeBase('20160131 120000');
        $this->assertEquals('20160229 120000', $object->nextMonth(1)->format('Ymd His'));
        
        $object = new StubDateTimeBase('20160229 120000');
        $this->assertEquals('20160529 120000', $object->nextMonth(3)->format('Ymd His'));
        
        $object = new StubDateTimeBase('20161031 120000');
        $this->assertEquals('20170228 120000', $object->nextMonth(4)->format('Ymd His'));
        
        //sub
        $object = new StubDateTimeBase('20170201 120000');
        $this->assertEquals('20170101 120000', $object->nextMonth(-1)->format('Ymd His'));
        
        $object = new StubDateTimeBase('20170331 120000');
        $this->assertEquals('20170228 120000', $object->nextMonth(-1)->format('Ymd His'));
        
        $object = new StubDateTimeBase('20160331 120000');
        $this->assertEquals('20160229 120000', $object->nextMonth(-1)->format('Ymd His'));
        
        $object = new StubDateTimeBase('20160531 120000');
        $this->assertEquals('20160229 120000', $object->nextMonth(-3)->format('Ymd His'));
        
        $object = new StubDateTimeBase('20170131 120000');
        $this->assertEquals('20160930 120000', $object->nextMonth(-4)->format('Ymd His'));
    }
    
    /**
    *   @test
    **/
    public function nextMonthException()
    {
//      $this->markTestIncomplete();
        
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('must be type int');
        //add
        $object = new StubDateTimeBase('20170201 120000');
        $object->nextMonth('DUMMY');
    }
    
    /**
    *   @test
    **/
    public function lastMonth()
    {
//      $this->markTestIncomplete();
        
        //add
        $object = new StubDateTimeBase('20170201 120000');
        $this->assertEquals('20170101 120000', $object->lastMonth()->format('Ymd His'));
    
        $object = new StubDateTimeBase('20160229 120000');
        $this->assertEquals('20160229 120000', $object->lastMonth(0)->format('Ymd His'));
        
        $object = new StubDateTimeBase('20170201 120000');
        $this->assertEquals('20170101 120000', $object->lastMonth(1)->format('Ymd His'));
        
        $object = new StubDateTimeBase('20170531 120000');
        $this->assertEquals('20160229 120000', $object->lastMonth(15)->format('Ymd His'));
        
        //sub
        $object = new StubDateTimeBase('20160131 120000');
        $this->assertEquals('20170228 120000', $object->lastMonth(-13)->format('Ymd His'));
    }
    
    /**
    *   @test
    **/
    public function isEndOfMonth()
    {
//      $this->markTestIncomplete();
        
        $object = new StubDateTimeBase('20160101 120000');
        $this->assertEquals(false, $object->isEndOfMonth());
        
        $object = new StubDateTimeBase('20160131 120000');
        $this->assertEquals(true, $object->isEndOfMonth());
        
        $object = new StubDateTimeBase('20160229 120000');
        $this->assertEquals(true, $object->isEndOfMonth());
        
        $object = new StubDateTimeBase('20160228 120000');
        $this->assertEquals(false, $object->isEndOfMonth());
        
        $object = new StubDateTimeBase('20170228 120000');
        $this->assertEquals(true, $object->isEndOfMonth());
    }
    
    /**
    *   @test
    **/
    public function nextYear()
    {
//      $this->markTestIncomplete();
        
        //add
        $object = new StubDateTimeBase('20170301 120000');
        $this->assertEquals('20180301 120000', $object->nextYear()->format('Ymd His'));
        
        $object = new StubDateTimeBase('20170201 120000');
        $this->assertEquals('20170201 120000', $object->nextYear(0)->format('Ymd His'));
        
        $object = new StubDateTimeBase('20160229 120000');
        $this->assertEquals('20170228 120000', $object->nextYear(1)->format('Ymd His'));
        
        $object = new StubDateTimeBase('20150301 120000');
        $this->assertEquals('20160301 120000', $object->nextYear(1)->format('Ymd His'));
        
        $object = new StubDateTimeBase('20160229 120000');
        $this->assertEquals('20140228 120000', $object->nextYear(-2)->format('Ymd His'));
        
        $object = new StubDateTimeBase('20160229 120000');
        $this->assertEquals('20120229 120000', $object->nextYear(-4)->format('Ymd His'));
    }
    
    /**
    *   @test
    **/
    public function lastYear()
    {
//      $this->markTestIncomplete();
        
        //add
        $object = new StubDateTimeBase('20170301 120000');
        $this->assertEquals('20160301 120000', $object->lastYear()->format('Ymd His'));
        
        $object = new StubDateTimeBase('20170201 120000');
        $this->assertEquals('20170201 120000', $object->lastYear(0)->format('Ymd His'));
        
        $object = new StubDateTimeBase('20160229 120000');
        $this->assertEquals('20150228 120000', $object->lastYear(1)->format('Ymd His'));
        
        $object = new StubDateTimeBase('20160229 120000');
        $this->assertEquals('20180228 120000', $object->lastYear(-2)->format('Ymd His'));
    }
    
    /**
    *   @test
    **/
    public function isLeapYear()
    {
//      $this->markTestIncomplete();
        
        $object = new StubDateTimeBase('20170301 120000');
        $this->assertEquals(false, $object->isLeapYear());
        
        $object = new StubDateTimeBase('20160101 120000');
        $this->assertEquals(true, $object->isLeapYear());
        
        $object = new StubDateTimeBase('20200101 120000');
        $this->assertEquals(true, $object->isLeapYear());
    }
    
    /**
    *   @test
    **/
    public function isToday()
    {
//      $this->markTestIncomplete();
        
        $object = new StubDateTimeBase();
        $this->assertEquals(true, $object->isToday());
        
        $object = $object->setTime(0, 0, 0);
        $this->assertEquals(true, $object->isToday());
        
        $object = $object->setTime(23, 59, 59);
        $this->assertEquals(true, $object->isToday());
        
        $object = $object->modify('+1 second');
        $this->assertEquals(false, $object->isToday());
    }
    
    /**
    *   @test
    **/
    public function isDay()
    {
//      $this->markTestIncomplete();
        
        //past
        $object = new StubDateTimeBase();
        $this->assertEquals(false, $object->isPastDay());
        
        $object = $object->setTime(0, 0, 0);
        $this->assertEquals(false, $object->isPastDay());
        
        $object = $object->modify('-1 day');
        $object = $object->setTime(23, 59, 59);
        $this->assertEquals(true, $object->isPastDay());
        
        //future
        $object = new StubDateTimeBase();
        $this->assertEquals(false, $object->isFutureDay());
        
        $object = $object->setTime(23, 59, 59);
        $this->assertEquals(false, $object->isFutureDay());
        
        $object = $object->modify('+1 day');
        $object = $object->setTime(0, 0, 0);
        $this->assertEquals(true, $object->isFutureDay());
    }
    
    /**
    *   @test
    **/
    public function isMonth()
    {
//      $this->markTestIncomplete();
        
        //this
        $object = new StubDateTimeBase();
        $this->assertEquals(true, $object->isThisMonth());
        
        $object = $object->setTime(0, 0, 0);
        $this->assertEquals(true, $object->isThisMonth());
        
        $object = $object->modify('+1 month');
        $this->assertEquals(false, $object->isThisMonth());
        
        //past
        $object = new StubDateTimeBase();
        $this->assertEquals(false, $object->isPastMonth());
        
        $object = $object->setTime(0, 0, 0);
        $this->assertEquals(false, $object->isPastMonth());
        
        $object = $object->modify('-1 month');
        $object = $object->setTime(23, 59, 59);
        $this->assertEquals(true, $object->isPastMonth());
        
        //future
        $object = new StubDateTimeBase();
        $this->assertEquals(false, $object->isFutureMonth());
        
        $object = $object->setTime(23, 59, 59);
        $this->assertEquals(false, $object->isFutureMonth());
        
        $object = $object->modify('+1 month');
        $object = $object->setTime(0, 0, 0);
        $this->assertEquals(true, $object->isFutureMonth());
    }
    
    /**
    *   @test
    **/
    public function isYear()
    {
//      $this->markTestIncomplete();
        
        //this
        $object = new StubDateTimeBase();
        $this->assertEquals(true, $object->isThisYear());
        
        $object = $object->setTime(0, 0, 0);
        $this->assertEquals(true, $object->isThisYear());
        
        $object = $object->modify('+1 year');
        $this->assertEquals(false, $object->isThisYear());
        
        //past
        $object = new StubDateTimeBase();
        $this->assertEquals(false, $object->isPastYear());
        
        $object = $object->setTime(0, 0, 0);
        $this->assertEquals(false, $object->isPastYear());
        
        $object = $object->modify('-1 year');
        $object = $object->setTime(23, 59, 59);
        $this->assertEquals(true, $object->isPastYear());
        
        //future
        $object = new StubDateTimeBase();
        $this->assertEquals(false, $object->isFutureYear());
        
        $object = $object->setTime(23, 59, 59);
        $this->assertEquals(false, $object->isFutureYear());
        
        $object = $object->modify('+1 year');
        $object = $object->setTime(0, 0, 0);
        $this->assertEquals(true, $object->isFutureYear());
    }
}
