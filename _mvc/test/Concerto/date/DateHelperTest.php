<?php

declare(strict_types=1);

namespace test\Concerto\date;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\date\DateHelper;
use DateTimeImmutable;
use DateTimeInterface;

//ユリウス日計算
//https://keisan.casio.jp/exec/system/1177639469

class DateHelperTest extends ConcertoTestCase
{
    public static function toJulianDayProvider()
    {
        return [
            [
                new DateTimeImmutable('2024-07-25 12:00:00'),
                2460517.0,
                60516.5,
            ],
            [
                new DateTimeImmutable('1899-12-31 00:00:00'),
                2415019.5,
                15019.0,
            ],
            [
                new DateTimeImmutable('1982-1-15 00:00:00'),
                2444984.5,
                44984.0,
            ],
            [
                new DateTimeImmutable('2982-1-15 00:00:00'),
                2810227.5,
                410227.0,
            ],
        ];
    }
    
    #[Test]
    #[DataProvider('toJulianDayProvider')]
    public function toJulianDay(
        DateTimeInterface $datetime,
        float $expectJD,
        float $expectMJD,
    )
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');
        
        $this->assertEquals(
            $expectJD,
            DateHelper::toJulianDay($datetime),
            'toJulianDay',
        );
        
        $this->assertEquals(
            $expectMJD,
            DateHelper::toModifiedJulianDay($datetime),
            'toModifiedJulianDay',
        );
    }

    public static function fromJulianDayProvider()
    {
        return [
            [
                2460517.0,
                60516.5,
                new DateTimeImmutable('2024-07-25 12:00:00'),
            ],
            [
                2415019.5,
                15019.0,
                new DateTimeImmutable('1899-12-31 00:00:00'),
            ],
            [
                2444984.5,
                44984.0,
                new DateTimeImmutable('1982-1-15 00:00:00'),
            ],
            [
                2810227.5,
                410227.0,
                new DateTimeImmutable('2982-1-15 00:00:00'),
            ],
        ];
    }
    
    #[Test]
    #[DataProvider('fromJulianDayProvider')]
    public function fromJulianDay(
        float $jd,
        float $mjd,
        DateTimeInterface $expect,
    )
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');
        
        $this->assertEquals(
            $expect,
            DateHelper::fromJulianDay($jd),
            'fromJulianDay',
        );
        
        $this->assertEquals(
            $expect,
            DateHelper::fromModifiedJulianDay($mjd),
            'fromModifiedJulianDay',
        );
    }
}
