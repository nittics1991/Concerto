<?php

namespace Concerto\test\domain\datetime;

use Concerto\test\ConcertoTestCase;
use Concerto\datetime\DatePeriodObject;
use Concerto\datetime\YearMonth;
use Concerto\datetime\DateObject;
use Concerto\datetime\DateTimeBaseInterface;

class DatePeriodObjectTest extends ConcertoTestCase
{
    /**
    *   @test
    **/
    public function basic()
    {
//      $this->markTestIncomplete();
        
        $start = new \DateTime('2016-12-1');
        $end = new \DateTimeImmutable('2016-12-31');
        $object = new DatePeriodObject($start, $end);
        
        $this->assertEquals($start, $object->getStartDay());
        $this->assertEquals($end, $object->getEndDay());
        $this->assertEquals(
            new \DateInterval('P1D'),
            $this->getPrivateProperty($object, 'interval')
        );
        $this->assertEquals(
            'DateTimeImmutable',
            $this->getPrivateProperty($object, 'callbackArgumentClass')
        );
        
        $interval = new \DateInterval('P2M');
        $object->setInterval($interval);
        $this->assertEquals(
            $interval,
            $this->getPrivateProperty($object, 'interval')
        );
        
        $cls = \DateTime::class;
        $object->setCallbackArgumentClass($cls);
        $this->assertEquals(
            $cls,
            $this->getPrivateProperty($object, 'callbackArgumentClass')
        );
    }
    
    /**
    *   @test
    **/
    public function setInterval()
    {
//      $this->markTestIncomplete();
        
        $start = new \DateTime('2016-12-1');
        $end = new \DateTimeImmutable('2016-12-31');
        $object = new DatePeriodObject($start, $end);
        
        $object->setIntervalYear(2);
        $this->assertEquals(
            new \DateInterval('P2Y'),
            $this->getPrivateProperty($object, 'interval')
        );
        
        $object->setIntervalMonth(2);
        $this->assertEquals(
            new \DateInterval('P2M'),
            $this->getPrivateProperty($object, 'interval')
        );
        
        $object->setIntervalWeek(2);
        $this->assertEquals(
            new \DateInterval('P2W'),
            $this->getPrivateProperty($object, 'interval')
        );
        
        $object->setIntervalDay(2);
        $this->assertEquals(
            new \DateInterval('P2D'),
            $this->getPrivateProperty($object, 'interval')
        );
        
        $object->setIntervalHour(2);
        $this->assertEquals(
            new \DateInterval('PT2H'),
            $this->getPrivateProperty($object, 'interval')
        );
        
        $object->setIntervalMinute(2);
        $this->assertEquals(
            new \DateInterval('PT2M'),
            $this->getPrivateProperty($object, 'interval')
        );
        
        $object->setIntervalSecond(2);
        $this->assertEquals(
            new \DateInterval('PT2S'),
            $this->getPrivateProperty($object, 'interval')
        );
    }
    
    /**
    *   @test
    **/
    public function othersSetMethod()
    {
//      $this->markTestIncomplete();
        
        $start = new \DateTime('2016-12-1');
        $end = new \DateTimeImmutable('2016-12-31');
        $object = new DatePeriodObject($start, $end);
        
        //callbackArgumentClass
        $object->setCallbackArgumentClass('DateTime');
        $this->assertEquals(
            'DateTime',
            $this->getPrivateProperty($object, 'callbackArgumentClass')
        );
        
        //setExcludeStartDate
        $this->assertEquals(
            false,
            $this->getPrivateProperty($object, 'excludeStartDate')
        );
        $object->setExcludeStartDate(true);
        $this->assertEquals(
            true,
            $this->getPrivateProperty($object, 'excludeStartDate')
        );
        
        //setExcludeEndDate
        $this->assertEquals(
            false,
            $this->getPrivateProperty($object, 'excludeEndDate')
        );
        $object->setExcludeEndDate(true);
        $this->assertEquals(
            true,
            $this->getPrivateProperty($object, 'excludeEndDate')
        );
    }
    
    public function eachProvider()
    {
        return [
            [
                new \DateTime('2016-1-14'),
                new \DateTimeImmutable('2016-12-31'),
                new \DateInterval('P2M'),
                \DateTime::class,
                false,
                false,
                [
                    '2016-01-14',
                    '2016-03-14',
                    '2016-05-14',
                    '2016-07-14',
                    '2016-09-14',
                    '2016-11-14',
                ]
            ],  //0
            [
                new \DateTime('2016-1-14 123456'),
                new \DateTimeImmutable('2016-1-20 123456'),
                null,
                \DateTimeImmutable::class,
                false,
                false,
                [
                    '2016-01-14',
                    '2016-01-15',
                    '2016-01-16',
                    '2016-01-17',
                    '2016-01-18',
                    '2016-01-19',
                    '2016-01-20',
                ]
            ],  //1
            [
                new \DateTime('2016-1-14 120000'),
                new \DateTimeImmutable('2016-1-20'),
                null,
                \DateTimeImmutable::class,
                false,
                false,
                [
                    '2016-01-14',
                    '2016-01-15',
                    '2016-01-16',
                    '2016-01-17',
                    '2016-01-18',
                    '2016-01-19',
                ]
            ],  //2
            [
                new \DateTime('2016-1-14 123456'),
                new \DateTimeImmutable('2016-1-20 123456'),
                null,
                \DateTimeImmutable::class,
                true,
                false,
                [
                    '2016-01-15',
                    '2016-01-16',
                    '2016-01-17',
                    '2016-01-18',
                    '2016-01-19',
                    '2016-01-20',
                ]
            ],  //3
            [
                new \DateTime('2016-1-14 123456'),
                new \DateTimeImmutable('2016-1-20 123456'),
                null,
                \DateTimeImmutable::class,
                false,
                true,
                [
                    '2016-01-14',
                    '2016-01-15',
                    '2016-01-16',
                    '2016-01-17',
                    '2016-01-18',
                    '2016-01-19',
                ]
            ],  //4
        ];
    }
    
    /**
    *   @test
    *  @dataProvider eachProvider
    **/
    public function iterate($start, $end, $interval, $cls, $exStart, $exEnd, $expect)
    {
//      $this->markTestIncomplete();
        
        $object = new DatePeriodObject($start, $end);
        
        if (isset($interval)) {
            $object->setInterval($interval);
        }
        
        $object
            ->setCallbackArgumentClass($cls)
            ->setExcludeStartDate($exStart)
            ->setExcludeEndDate($exEnd)
        ;
        
        $cnt = 0;
        
        foreach ($object as $date) {
            $this->assertInstanceOf($cls, $date);
            $this->assertEquals($expect[$cnt], $date->format('Y-m-d'));
            $cnt++;
        }
        $this->assertEquals(count($expect), $cnt);
    }
    
    /**
    *   @test
    **/
    public function diff()
    {
//      $this->markTestIncomplete();
        
        $start = new \DateTime('2017-2-1');
        $end = new \DateTime('2017-3-1');
        $object = new DatePeriodObject($start, $end);
        
        $actual = new \DateInterval('P28D');
        $expect = $object->diff();
        $this->assertEquals($actual->format('%r%d'), $expect->format('%r%d'));
        $this->assertEquals('28', $expect->format('%r%d'));
        $this->assertEquals('28', $expect->format('%r%a'));
        
        
        $start = new \DateTime('2017-4-1');
        $end = new \DateTime('2017-5-1');
        $object = new DatePeriodObject($start, $end);
        $expect = $object->diff();
        
        $start = new \DateTime('2017-2-1');
        $end = new \DateTime('2017-2-10');
        $object = new DatePeriodObject($start, $end);
        
        $actual = new \DateInterval('P9D');
        $expect = $object->diff();
        $this->assertEquals($actual->format('%r%d'), $expect->format('%r%d'));
        $this->assertEquals('9', $expect->format('%r%d'));
        $this->assertEquals('9', $expect->format('%r%a'));
    }
    
    /**
    *   @test
    **/
    public function diffYear()
    {
//      $this->markTestIncomplete();
        
        $start = new \DateTime('2013-2-1');
        $end = new \DateTime('2017-4-1');
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals(4, $object->diffYear());
        
        $start = new \DateTime('2017-1-1');
        $end = new \DateTime('2014-4-1');
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals(-2, $object->diffYear());
        
        $start = new \DateTime('2016-2-29');
        $end = new \DateTime('2017-2-28');
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals(1, $object->diffYear());
        
        $start = new \DateTime('2018-2-28');
        $end = new \DateTime('2016-2-29');
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals(-2, $object->diffYear());
        
        $start = new \DateTime('2017-6-30');
        $end = new \DateTime('2018-7-30');
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals(1, $object->diffYear());
    }
    
    /**
    *   @test
    **/
    public function diffMonth()
    {
//      $this->markTestIncomplete();
        
        //plus
        $start = new \DateTime('2017-1-1');
        $end = new \DateTime('2017-2-1');
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals(1, $object->diffMonth());
        
        $start = new \DateTime('2017-2-1');
        $end = new \DateTime('2017-4-1 120000');
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals(2, $object->diffMonth());
        
        $start = new \DateTime('2017-2-1 120000');
        $end = new \DateTime('2017-3-1');
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals(0, $object->diffMonth());
        
        //both end of month s>e
        $start = new \DateTime('2016-2-29 120000');
        $end = new \DateTime('2017-2-28 120000');
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals(12, $object->diffMonth());
        
        $start = new \DateTime('2016-2-29 000000');
        $end = new \DateTime('2017-2-28 120000');
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals(12, $object->diffMonth());
        
        $start = new \DateTime('2016-2-29 120000');
        $end = new \DateTime('2017-2-28 000000');
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals(11, $object->diffMonth());
        
        //both end of month s>e
        $start = new \DateTime('2016-9-30 120000');
        $end = new \DateTime('2017-10-31 120000');
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals(13, $object->diffMonth());
        
        $start = new \DateTime('2016-9-30 000000');
        $end = new \DateTime('2017-10-31 120000');
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals(13, $object->diffMonth());
        
        $start = new \DateTime('2016-9-30 120000');
        $end = new \DateTime('2017-10-31 000000');
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals(12, $object->diffMonth());
        
        //s end of month
        $start = new \DateTime('2016-9-30 120000');
        $end = new \DateTime('2017-10-30 120000');
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals(13, $object->diffMonth());
        
        $start = new \DateTime('2016-2-29 120000');
        $end = new \DateTime('2016-3-30 120000');
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals(1, $object->diffMonth());
        
        //e end of month
        $start = new \DateTime('2016-9-29 120000');
        $end = new \DateTime('2017-10-31 120000');
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals(13, $object->diffMonth());
        
        $start = new \DateTime('2016-12-29 120000');
        $end = new \DateTime('2017-2-28 120000');
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals(1, $object->diffMonth());
        
        //minus
        $start = new \DateTime('2017-10-31 120000');
        $end = new \DateTime('2016-9-30 120000');
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals(-13, $object->diffMonth());
    }
    
    /**
    *   @test
    **/
    public function diffWeek2Second()
    {
//      $this->markTestIncomplete();
        
        //week
        $start = new \DateTime('2017-3-1');    //Wed
        $end = new \DateTime('2017-3-15');      //Tue
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals(2, $object->diffWeek());
        
        //day
        $start = new \DateTime('2013-4-1');
        $end = new \DateTime('2013-4-11');
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals(10, $object->diffDay());
        
        $start = new \DateTime('2013-4-1 120000');
        $end = new \DateTime('2013-4-11');
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals(9, $object->diffDay());
        
        //hour
        $start = new \DateTime('2017-1-1');
        $end = new \DateTime('2017-1-3');
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals(48, $object->diffHour());
        
        //minute
        $start = new \DateTime('2017-1-1 000000');
        $end = new \DateTime('2017-1-2 000000');
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals(24 * 60, $object->diffMinute());
        
        //second
        $start = new \DateTime('2017-1-1 000000');
        $end = new \DateTime('2017-1-2 000000');
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals(24 * 60 * 60, $object->diffSecond());
    }
    
    /**
    *   @test
    **/
    public function domainClass()
    {
//      $this->markTestIncomplete();
        
        $start = new DateObject('2017-1-1 123456');
        $end = new DateObject('2017-1-10 000000');
        
        $object = new DatePeriodObject($start, $end);
        $this->assertEquals($start, $object->getStartDay());
        $this->assertEquals($end, $object->getEndDay());
        
        $i = 0;
        foreach ($object as $date) {
            // var_dump($date);echo "<hr>\r";
            
            $i++;
        }
        //time = 000000
        $this->assertEquals(10, $i);
    }
    
    /**
    *   @test
    **/
    public function createFiscalYear()
    {
//      $this->markTestIncomplete();
        
        $object = DatePeriodObject::createFiscalYear('2016s');
        $this->assertEquals(
            new \DateTimeImmutable('2016-10-01 000000'),
            $object->getStartDay()
        );
        $this->assertEquals(
            new \DateTimeImmutable('2017-03-31 235959'),
            $object->getEndDay()
        );
        
        $object = DatePeriodObject::createFiscalYear('2016K');
        $this->assertEquals(
            new \DateTimeImmutable('2016-04-01 000000'),
            $object->getStartDay()
        );
        $this->assertEquals(
            new \DateTimeImmutable('2016-09-30 235959'),
            $object->getEndDay()
        );
    }
    
    /**
    *   @test
    **/
    public function createYear()
    {
//      $this->markTestIncomplete();
        
        $object = DatePeriodObject::createYear(2017);
        $this->assertEquals(
            new \DateTimeImmutable('2017-01-1 000000'),
            $object->getStartDay()
        );
        
        $this->assertEquals(
            new \DateTimeImmutable('2017-12-31 235959'),
            $object->getEndDay()
        );
        
        $object = DatePeriodObject::createYear(2017, 4);
        $this->assertEquals(
            new \DateTimeImmutable('2017-04-1 000000'),
            $object->getStartDay()
        );
        
        $this->assertEquals(
            new \DateTimeImmutable('2018-03-31 235959'),
            $object->getEndDay()
        );
        
        $object = DatePeriodObject::createYear(2017, 4, 3);
        $this->assertEquals(
            new \DateTimeImmutable('2017-04-1 000000'),
            $object->getStartDay()
        );
        
        $this->assertEquals(
            new \DateTimeImmutable('2020-03-31 235959'),
            $object->getEndDay()
        );
    }
    
    /**
    *   @test
    **/
    public function createMonth()
    {
//      $this->markTestIncomplete();
        
        $object = DatePeriodObject::createMonth(2017);
        $this->assertEquals(
            new \DateTimeImmutable('2017-01-1 000000'),
            $object->getStartDay()
        );
        
        $this->assertEquals(
            new \DateTimeImmutable('2017-01-31 235959'),
            $object->getEndDay()
        );
        
        $object = DatePeriodObject::createMonth(2016, 2);
        $this->assertEquals(
            new \DateTimeImmutable('2016-02-1 000000'),
            $object->getStartDay()
        );
        
        $this->assertEquals(
            new \DateTimeImmutable('2016-02-29 235959'),
            $object->getEndDay()
        );
        
        $object = DatePeriodObject::createMonth(2016, 2, 3);
        $this->assertEquals(
            new \DateTimeImmutable('2016-02-1 000000'),
            $object->getStartDay()
        );
        
        $this->assertEquals(
            new \DateTimeImmutable('2016-04-30 235959'),
            $object->getEndDay()
        );
    }
    
    /**
    *   @test
    **/
    public function createWeek()
    {
//      $this->markTestIncomplete();
        
        $object = DatePeriodObject::createWeek('2017-3-1');
        $this->assertEquals(
            new \DateTimeImmutable('2017-03-1 000000'),
            $object->getStartDay()
        );
        
        $this->assertEquals(
            new \DateTimeImmutable('2017-03-7 235959'),
            $object->getEndDay()
        );
        
        $object = DatePeriodObject::createWeek('2017-2-1', 4);
        $this->assertEquals(
            new \DateTimeImmutable('2017-02-1 000000'),
            $object->getStartDay()
        );
        
        $this->assertEquals(
            new \DateTimeImmutable('2017-02-28 235959'),
            $object->getEndDay()
        );
    }
    
    /**
    *   @test
    **/
    public function createDay()
    {
//      $this->markTestIncomplete();
        
        $object = DatePeriodObject::createDay('2017-3-1');
        $this->assertEquals(
            new \DateTimeImmutable('2017-03-1 000000'),
            $object->getStartDay()
        );
        
        $this->assertEquals(
            new \DateTimeImmutable('2017-03-1 235959'),
            $object->getEndDay()
        );
        
        $object = DatePeriodObject::createDay('2017-2-1', 4);
        $this->assertEquals(
            new \DateTimeImmutable('2017-02-1 000000'),
            $object->getStartDay()
        );
        
        $this->assertEquals(
            new \DateTimeImmutable('2017-02-4 235959'),
            $object->getEndDay()
        );
    }
}
