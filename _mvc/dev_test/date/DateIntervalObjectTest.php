<?php

declare(strict_types=1);

namespace test\Concerto\data;

use PHPUnit\Framework\TestCase;
use test\Concerto\{
    ExpansionAssertionTrait,
    PrivateTestTrait,
};
use DateInterval;
use DateTimeImmutable;
use Concerto\date\{
    DateObject,
    DateIntervalObject,
};

class DateIntervalObjectTest extends TestCase
{
    use PrivateTestTrait;
    use ExpansionAssertionTrait;

    protected function setUp(): void
    {
        ini_set('date.timezone', 'Asia/Tokyo');
    }

    public function _constructProvider()
    {
        return [
            [
                'P1Y2M3DT4H5M6S',
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider _constructProvider
    */
    public function _construct(
        string $duration,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = new DateIntervalObject(
            $duration,
        );

        $this->assertEquals(
            new DateInterval($duration),
            $obj->toDateInterval(),
        );
    }

    public function createFromDateIntervalProvider()
    {
        return [
            [
                new DateInterval('P1Y2M3DT4H5M6S'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider createFromDateIntervalProvider
    */
    public function createFromDateInterval(
        DateInterval $interval,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = DateIntervalObject::createFromDateInterval(
            $interval,
        );

        $this->assertEquals(
            $interval,
            $obj->toDateInterval(),
        );
    }

    public function createFromDateStringProvider()
    {
        return [
            [
                '1 day + 12 hours',
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider createFromDateStringProvider
    */
    public function createFromDateString(
        string $datetime,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = DateIntervalObject::createFromDateString(
            $datetime,
        );

        $this->assertEquals(
            DateInterval::createFromDateString(
                $datetime,
            ),
            $obj->toDateInterval(),
        );
    }

    public function formatProvider()
    {
        return [
            [
                'P1Y2M3DT4H5M6S',
                '%y-%m-%d %h:%i:%s',
                '1-2-3 4:5:6',
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider formatProvider
    */
    public function format(
        string $interval,
        string $format,
        string $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = new DateIntervalObject(
            $interval,
        );

        $this->assertEquals(
            $expect,
            $obj->format(
                $format,
            ),
        );
    }

    public function datePropertyProvider()
    {
        return [
            [
                'P1Y2M3DT4H5M6S',
                1,
                2,
                3,
                4,
                5,
                6,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider datePropertyProvider
    */
    public function dateProperty(
        string $interval,
        int $expect_y,
        int $expect_m,
        int $expect_d,
        int $expect_h,
        int $expect_i,
        int $expect_s,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = new DateIntervalObject(
            $interval,
        );

        $this->assertEquals(
            $expect_y,
            $obj->year(),
        );

        $this->assertEquals(
            $expect_m,
            $obj->month(),
        );

        $this->assertEquals(
            $expect_d,
            $obj->day(),
        );

        $this->assertEquals(
            $expect_h,
            $obj->hour(),
        );

        $this->assertEquals(
            $expect_i,
            $obj->minute(),
        );

        $this->assertEquals(
            $expect_s,
            $obj->Second(),
        );
    }

    public function milliSecondProvider()
    {
        return [
            [
                new DateTimeImmutable('2001-6-23 01:23:45.123456'),
                new DateTimeImmutable('2001-6-23 01:23:45.000000'),
                -123.456,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider milliSecondProvider
    */
    public function milliSecond(
        DateTimeImmutable $src_date,
        DateTimeImmutable $dest_date,
        float $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $dateInterval = $src_date->diff($dest_date);

        $obj = DateIntervalObject::createFromDateInterval(
            $dateInterval,
        );

        $this->assertEquals(
            $expect,
            $obj->milliSecond(),
        );
    }

    public function microSecondProvider()
    {
        return [
            [
                new DateTimeImmutable('2001-6-23 01:23:45.123456'),
                new DateTimeImmutable('2001-6-23 01:23:45.000000'),
                -123456,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider microSecondProvider
    */
    public function microSecond(
        DateTimeImmutable $src_date,
        DateTimeImmutable $dest_date,
        float $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $dateInterval = $src_date->diff($dest_date);

        $obj = DateIntervalObject::createFromDateInterval(
            $dateInterval,
        );

        $this->assertEquals(
            $expect,
            $obj->microSecond(),
        );
    }

    public function daysProvider()
    {
        return [
            [
                new DateTimeImmutable('2001-6-23 01:23:45.123456'),
                new DateTimeImmutable('2002-6-20 01:23:45.123456'),
                362,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider daysProvider
    */
    public function days(
        DateTimeImmutable $src_date,
        DateTimeImmutable $dest_date,
        int $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $dateInterval = $src_date->diff($dest_date);

        $obj = DateIntervalObject::createFromDateInterval(
            $dateInterval,
        );

        $this->assertEquals(
            $expect,
            $obj->days(),
        );
    }
}
