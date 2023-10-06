<?php

declare(strict_types=1);

namespace candidate_test\ROOT;

use DateTimeImmutable;
use test\Concerto\ConcertoTestCase;
use candidate\ROOT\DateTimeUtil;

class DateTimeUtilTest extends ConcertoTestCase
{
    protected function setUp(): void
    {
        ini_set('date.timezone', 'Asia/Tokyo');
    }

    public function testSuccessPeriodDate()
    {
        $expect = ['2016-02-27', '2016-02-28', '2016-02-29', '2016-03-01', '2016-03-02'];
        $this->assertEquals(
            $expect,
            DateTimeUtil::periodDate('20160227', '2016-03-02', 'P1D', 'Y-m-d')
        );

        $expect = ['201510', '201511', '201512', '201601', '201602', '201603'];
        $this->assertEquals(
            $expect,
            DateTimeUtil::periodDate('20151001', '2016-03-01', 'P1M', 'Ym')
        );
    }

    /**
    */
    public function testExceptionPeriodDate1()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('greater than end of the start');
        //start < end
        DateTimeUtil::periodDate('2016-03-02', '20160227', 'P1D', 'Y-m-d');
    }

    /**
    */
    public function testExceptionPeriodDate2()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('limit is less 1000');
        //set limit exceed PERIOD_MAX
        DateTimeUtil::periodDate('20160227', '20160302', 'P1D', 'Y-m-d', 9999);
    }

    /**
    */
    public function testExceptionPeriodDate3()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('it exceeded the number of processing times');
        //counter over limit
        DateTimeUtil::periodDate('20160227', '20170302', 'P1D', 'Y-m-d', 10);
    }

    public function testSuccessGetIntervalYYYYMMDD()
    {
        $expect = ['20160227', '20160228', '20160229', '20160301', '20160302'];
        $this->assertEquals(
            $expect,
            DateTimeUtil::getIntervalYYYYMMDD('20160227', '2016-03-02')
        );

        //期間が0
        $expect = ['20151003'];
        $this->assertEquals(
            $expect,
            DateTimeUtil::getIntervalYYYYMMDD('20151003', '20151003')
        );
    }

    public function testSuccessGetIntervalYYYYMM()
    {
        $expect = ['201510', '201511', '201512', '201601', '201602', '201603'];
        $this->assertEquals(
            $expect,
            DateTimeUtil::getIntervalYYYYMM('20151001', '2016-03-01')
        );

        //日が小さい
        $expect = ['201510', '201511', '201512', '201601', '201602'];
        $this->assertEquals(
            $expect,
            DateTimeUtil::getIntervalYYYYMM('20151020', '20160319')
        );

        //日が同じ
        $expect = ['201510', '201511', '201512', '201601', '201602', '201603'];
        $this->assertEquals(
            $expect,
            DateTimeUtil::getIntervalYYYYMM('20151020', '20160320')
        );

        //期間が0
        $expect = ['201510'];
        $this->assertEquals(
            $expect,
            DateTimeUtil::getIntervalYYYYMM('20151020', '20151020')
        );
    }

    public function testSuccessGgetNoWeekToDate()
    {
        $expect = new DateTimeImmutable('2016-2-23 00:00:00');
        $actual = DateTimeUtil::getNoWeekToDate(2016, 2, 4, 2);
        $this->assertEquals($expect, $actual);

        $expect = new DateTimeImmutable('2016-1-31 00:00:00');
        $actual = DateTimeUtil::getNoWeekToDate(2016, 1, 5, 0);
        $this->assertEquals($expect, $actual);

        $expect = new DateTimeImmutable('2016-01-31 00:00:00');
        $actual = DateTimeUtil::getNoWeekToDate(2016, 2, 0, 0);
        $this->assertEquals($expect, $actual);

        $expect = new DateTimeImmutable('2016-01-24 00:00:00');
        $actual = DateTimeUtil::getNoWeekToDate(2016, 2, -1, 0);
        $this->assertEquals($expect, $actual);

        $expect = new DateTimeImmutable('2016-03-27 00:00:00');
        $actual = DateTimeUtil::getNoWeekToDate(2016, 2, 8, 0);
        $this->assertEquals($expect, $actual);
    }

    public function testYYYYMMDDaddSlash()
    {
        $this->assertEquals('2016/12/31', DateTimeUtil::YYYYMMDDaddSlash('20161231'));
        $this->assertEquals('2016/02/01', DateTimeUtil::YYYYMMDDaddSlash('20160201'));
        $this->assertEquals('201621', DateTimeUtil::YYYYMMDDaddSlash('201621'));
    }

    public function testYYYYMMDDaddHyphen()
    {
        $this->assertEquals('2016-12-31', DateTimeUtil::YYYYMMDDaddHyphen('20161231'));
        $this->assertEquals('2016-02-01', DateTimeUtil::YYYYMMDDaddHyphen('20160201'));
        $this->assertEquals('201621', DateTimeUtil::YYYYMMDDaddHyphen('201621'));
    }

    public function modifyYYYYMMProvider()
    {
        return [
            ['202104', 0, '202104'],
            ['202104', 2, '202106'],
            ['202104', -2, '202102'],
            ['202104', 12, '202204'],
            ['202104', -12, '202004'],
            ['202104', 14, '202206'],
            ['202104', -14, '202002'],
        ];
    }

    /**
    *   @test
    *   @dataProvider modifyYYYYMMProvider
    */
    public function testModifyYYYYMM($data, $interval, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            DateTimeUtil::modifyYYYYMM($data, $interval),
        );
    }
}
