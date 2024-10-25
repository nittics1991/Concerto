<?php

declare(strict_types=1);

namespace dev_test\array;

use test\Concerto\ConcertoTestCase;
use candidate\util\DateUtil;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Concerto\FiscalYear;

class DateUtilTest extends ConcertoTestCase
{
    public function toDateTimeImmutableProvider()
    {
        return [
            [
                new DateTimeImmutable(),
            ],
            [
                new DateTime(),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider toDateTimeImmutableProvider
    */
    public function toDateTimeImmutable(
        DateTimeInterface $date,
    ) {
//      $this->markTestIncomplete();

        $obj = new DateUtil($date);

        $actual = $this->getPrivateProperty(
            $obj,
            'date',
        );

        $this->assertInstanceOf(
            DateTimeImmutable::class,
            $actual,
        );

        $actual2 = $obj->toDateTimeImmutable();

        $this->assertInstanceOf(
            DateTimeImmutable::class,
            $actual2,
        );

        $this->assertEquals(
            $actual,
            $actual2,
        );
    }

    /**
    *   @test
    */
    public function now()
    {
     // $this->markTestIncomplete();

        $this->assertEqualsWithDelta(
            new DateTimeImmutable(),
            (DateUtil::now())
                ->toDateTimeImmutable(),
            1,
        );
    }

    /**
    *   @test
    */
    public function today()
    {
     // $this->markTestIncomplete();

        $this->assertEquals(
            (new DateTimeImmutable())
                ->format('Ymd000000'),
            (DateUtil::today())
                ->toDateTimeImmutable()
                ->format('YmdHis'),
        );
    }

    /**
    *   @test
    */
    public function month()
    {
     // $this->markTestIncomplete();

        $this->assertEquals(
            (new DateTimeImmutable())
                ->format('Ym01000000'),
            (DateUtil::month())
                ->toDateTimeImmutable()
                ->format('YmdHis'),
        );
    }

    public function fromHalfProvider()
    {
        return [
            [
                '2021K',
                DateTimeImmutable::createFromFormat(
                    '!Ym',
                    '202104',
                ),
            ],
            [
                '2021S',
                DateTimeImmutable::createFromFormat(
                    '!Ym',
                    '202110',
                ),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider fromHalfProvider
    */
    public function fromHalf(
        string $half,
        DateTimeInterface $expect,
    ) {
     // $this->markTestIncomplete();

        $this->assertEquals(
            $expect->format('Ym01000000'),
            (DateUtil::fromHalf($half))
                ->toDateTimeImmutable()
                ->format('YmdHis'),
        );
    }

    /**
    *   @test
    */
    public function half()
    {
     // $this->markTestIncomplete();

        $year = (int)date('Y');
        $month = (int)date('n');

        if ($month >= 4 && $month <= 9) {
            $yyyymm = "{$year}04";
        } elseif ($month >= 10 && $month <= 12) {
            $yyyymm = "{$year}10";
        } else {
            $year--;
            $yyyymm = "{$year}10";
        }

        $expect = DateTimeImmutable::createFromFormat(
            '!Ym',
            $yyyymm,
        );

        $this->assertEquals(
            $expect->format('Ym01000000'),
            (DateUtil::half())
                ->toDateTimeImmutable()
                ->format('YmdHis'),
        );
    }

    public function fromMonthProvider()
    {
        return [
            [
                '202107',
                DateTimeImmutable::createFromFormat(
                    '!Ym',
                    '202107',
                ),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider fromMonthProvider
    */
    public function fromMonth(
        string $yyyymm,
        DateTimeInterface $expect,
    ) {
     // $this->markTestIncomplete();

        $this->assertEquals(
            $expect->format('Ym01000000'),
            (DateUtil::fromMonth($yyyymm))
                ->toDateTimeImmutable()
                ->format('YmdHis'),
        );
    }

    public function fromDateProvider()
    {
        return [
            [
                '20210729',
                DateTimeImmutable::createFromFormat(
                    '!Ymd',
                    '20210729',
                ),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider fromDateProvider
    */
    public function fromDate(
        string $yyyymm,
        DateTimeInterface $expect,
    ) {
     // $this->markTestIncomplete();

        $this->assertEquals(
            $expect->format('Ymd000000'),
            (DateUtil::fromDate($yyyymm))
                ->toDateTimeImmutable()
                ->format('YmdHis'),
        );
    }

    public function toHalfProvider()
    {
        return [
            [
                DateTimeImmutable::createFromFormat(
                    '!Ymd',
                    '20210729',
                ),
                '2021K',
            ],
            [
                DateTimeImmutable::createFromFormat(
                    '!Ymd',
                    '20220228',
                ),
                '2021S',
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider toHalfProvider
    */
    public function toHalf(
        DateTimeInterface $date,
        string $expect,
    ) {
     // $this->markTestIncomplete();

        $this->assertEquals(
            $expect,
            (new DateUtil($date))
                ->toHalf()
        );
    }

    public function toTimeProvider()
    {
        return [
            [
                DateTimeImmutable::createFromFormat(
                    '!YmdHis',
                    '20210729123456',
                ),
                '20210729 123456',
            ],
            [
                DateTimeImmutable::createFromFormat(
                    '!YmdHis',
                    '20220228223344',
                ),
                '20220228 223344',
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider toTimeProvider
    */
    public function toTime(
        DateTimeInterface $date,
        string $expect,
    ) {
     // $this->markTestIncomplete();

        $this->assertEquals(
            $expect,
            (new DateUtil($date))
                ->toTime()
        );
    }

    public function toDateProvider()
    {
        return [
            [
                DateTimeImmutable::createFromFormat(
                    '!Ymd',
                    '20210729',
                ),
                '20210729',
            ],
            [
                DateTimeImmutable::createFromFormat(
                    '!Ymd',
                    '20220228',
                ),
                '20220228',
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider toDateProvider
    */
    public function toDate(
        DateTimeInterface $date,
        string $expect,
    ) {
     // $this->markTestIncomplete();

        $this->assertEquals(
            $expect,
            (new DateUtil($date))
                ->toDate()
        );
    }

    public function toMonthProvider()
    {
        return [
            [
                DateTimeImmutable::createFromFormat(
                    '!Ymd',
                    '20210729',
                ),
                '202107',
            ],
            [
                DateTimeImmutable::createFromFormat(
                    '!Ymd',
                    '20220228',
                ),
                '202202',
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider toMonthProvider
    */
    public function toMonth(
        DateTimeInterface $date,
        string $expect,
    ) {
     // $this->markTestIncomplete();

        $this->assertEquals(
            $expect,
            (new DateUtil($date))
                ->toMonth()
        );
    }

    public function toFirstHalfMonthProvider()
    {
        return [
            [
                DateTimeImmutable::createFromFormat(
                    '!Ymd',
                    '20210729',
                ),
                '202104',
            ],
            [
                DateTimeImmutable::createFromFormat(
                    '!Ymd',
                    '20220228',
                ),
                '202110',
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider toFirstHalfMonthProvider
    */
    public function toFirstHalfMonth(
        DateTimeInterface $date,
        string $expect,
    ) {
     // $this->markTestIncomplete();

        $this->assertEquals(
            $expect,
            (new DateUtil($date))
                ->toFirstHalfMonth()
        );
    }

    public function toLastHalfMonthProvider()
    {
        return [
            [
                DateTimeImmutable::createFromFormat(
                    '!Ymd',
                    '20210729',
                ),
                '202109',
            ],
            [
                DateTimeImmutable::createFromFormat(
                    '!Ymd',
                    '20220228',
                ),
                '202203',
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider toLastHalfMonthProvider
    */
    public function toLastHalfMonth(
        DateTimeInterface $date,
        string $expect,
    ) {
     // $this->markTestIncomplete();

        $this->assertEquals(
            $expect,
            (new DateUtil($date))
                ->toLastHalfMonth()
        );
    }

    public function addIntervalProvider()
    {
        return [
            [
                new DateTimeImmutable('2021-07-29'),
                'P3M',
                new DateTimeImmutable('2021-10-29'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider addIntervalProvider
    */
    public function addInterval(
        DateTimeImmutable $date,
        string $interval,
        DateTimeImmutable $expect,
    ) {
     // $this->markTestIncomplete();

        $this->assertEquals(
            $expect,
            (new DateUtil($date))
                ->addInterval($interval)
                ->toDateTimeImmutable(),
        );
    }

    public function subIntervalProvider()
    {
        return [
            [
                new DateTimeImmutable('2021-07-29'),
                'P3M',
                new DateTimeImmutable('2021-04-29'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider subIntervalProvider
    */
    public function subInterval(
        DateTimeImmutable $date,
        string $interval,
        DateTimeImmutable $expect,
    ) {
     // $this->markTestIncomplete();

        $this->assertEquals(
            $expect,
            (new DateUtil($date))
                ->subInterval($interval)
                ->toDateTimeImmutable(),
        );
    }
}
