<?php

declare(strict_types=1);

namespace test\Concerto\data;

use PHPUnit\Framework\TestCase;
use test\Concerto\{
    ExpansionAssertionTrait,
    PrivateTestTrait,
};
use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;
use Concerto\date\{
    DateInterface,
    DateIntervalInterface,
    DatePeriodInterface,
    DateTimeZoneInterface,
};
use Concerto\date\{
    DateObject,
    DateIntervalObject,
    DatePeriodObject,
    DateTimeZoneObject,
};

/*
*   used thisHalf,thisQuater
*/
class TestDateObjectStub1 extends DateObject
{
    const TODAY = '2001-6-23 01:23:45';

    public static function today(): DateInterface
    {
        return new self(static::TODAY);
    }
}

/////////////////////////////////////////////////////

class DateObjectTest extends TestCase
{
    use PrivateTestTrait;
    use ExpansionAssertionTrait;

    protected function setUp(): void
    {
        ini_set('date.timezone', 'Asia/Tokyo');
    }

    /**
    *   @test
    */
    public function preliminaryConfirmation()
    {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = new DateObject();

        $this->assertInstanceOf(
            DateTimeImmutable::class,
            $this->getPrivateProperty(
                $obj,
                'datetime',
            ),
        );

        $this->assertInstanceOf(
            DateInterface::class,
            $obj,
        );

        $this->assertInstanceOf(
            DateTimeImmutable::class,
            $obj->toDateTimeImmutable(),
        );

        $this->assertInstanceOf(
            DateTime::class,
            $obj->toDateTime(),
        );

        $this->assertInstanceOf(
            DateTimeZoneInterface::class,
            $obj->timezone(),
        );

        $this->assertIsInt(
            $obj->fiscalStartMonth(),
        );

        $this->assertGreaterThanOrEqual(
            1,
            $obj->fiscalStartMonth(),
        );

        $this->assertLessThanOrEqual(
            12,
            $obj->fiscalStartMonth(),
        );
    }

    public function _constructProvider()
    {
        return [
            //all null
            [
                null,
                null,
                null,
                new DateTimeImmutable(),
                new DateTimeZoneObject(
                    date_default_timezone_get(),
                ),
                4,
            ],
            //set $datetime
            [
                'now',
                null,
                null,
                new DateTimeImmutable(),
                new DateTimeZoneObject(
                    date_default_timezone_get(),
                ),
                4,
            ],
            //set $timezone
            [
                null,
                new DateTimeZoneObject(
                    'UTC',
                ),
                null,
                new DateTimeImmutable(),
                new DateTimeZoneObject('UTC'),
                4,
            ],
            //set $fiscal_start_month
            [
                null,
                null,
                10,
                new DateTimeImmutable(),
                new DateTimeZoneObject(
                    date_default_timezone_get(),
                ),
                10,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider _constructProvider
    */
    public function _construct(
        ?string $datetime,
        ?DateTimezoneInterface $timezone,
        ?int $fiscal_start_month,
        DateTimeImmutable $expected_datetime,
        DateTimeZoneInterface $expected_timezone,
        int $expected_fiscal_start_month,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = new DateObject(
            $datetime,
            $timezone,
            $fiscal_start_month,
        );

        $this->assertEqualsDateTime(
            $expected_datetime,
            $obj->toDateTimeImmutable(),
        );

        $this->assertEquals(
            $expected_timezone,
            $obj->timezone(),
        );

        $this->assertEquals(
            $expected_fiscal_start_month,
            $obj->fiscalStartMonth(),
        );
    }

    /**
    *   @test
    */
    public function format()
    {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $expect = new DateTimeImmutable('2001-01-23 12:34:56');
        $actual = new DateObject('2001-01-23 12:34:56');

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    /**
    *   @test
    */
    public function createFromInterface()
    {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $date_string = '2001-01-23 12:34:56+1300';

        $expect = new DateTime($date_string);
        $actual = DateObject::createFromInterface($expect);

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );

        $expect = new DateTimeImmutable($date_string);
        $actual = DateObject::createFromInterface($expect);

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function createFromFormatProvider()
    {
        return [
            //timezone null
            [
                'Y-m-d H:i:s',
                '2001-01-23 23:45:12',
                null,
                DateTimeImmutable::createFromFormat(
                    'Y-m-d H:i:s',
                    '2001-01-23 23:45:12',
                ),
            ],
            //set timezone
            [
                'Y-m-d H:i:s',
                '2001-01-23 23:45:12',
                new DateTimeZoneObject(
                    'UTC',
                ),
                DateTimeImmutable::createFromFormat(
                    'Y-m-d H:i:s',
                    '2001-01-23 23:45:12',
                    new DateTimeZone(
                        'UTC',
                    ),
                ),
            ],
            //reset of undefined time item
            [
                'Y-m-d H',
                '2001-01-23 23',
                null,
                DateTimeImmutable::createFromFormat(
                    'Y-m-d H:i:s',
                    '2001-01-23 23:00:00',
                ),
            ],
            //reset of undefined date item
            [
                'Y-m',
                '2001-09',
                null,
                DateTimeImmutable::createFromFormat(
                    'Y-m-d H:i:s',
                    '2001-09-01 00:00:00',
                ),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider createFromFormatProvider
    */
    public function createFromFormat(
        string $format,
        string $datetime,
        ?DateTimeZoneInterface $timezone = null,
        DateTimeImmutable $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual = DateObject::createFromFormat(
            $format,
            $datetime,
            $timezone,
        );

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    /**
    *   @test
    */
    public function createFromFormatException()
    {
        //$this->markTestIncomplete('---markTestIncomplete---');

        try {
            DateObject::createFromFormat(
                'Y-m-d',
                '2001/6/1',
            );
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(
                "invalid argument",
                $e->getMessage(),
            );
            return;
        }
        $this->assertTrue(false);
    }

    /**
    *   @test
    */
    public function now()
    {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEqualsDateTime(
            new DateObject('now'),
            DateObject::now(),
        );
    }

    /**
    *   @test
    */
    public function today()
    {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEqualsDateTime(
            new DateObject('today'),
            DateObject::today(),
        );
    }

    /**
    *   @test
    */
    public function yesterday()
    {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEqualsDateTime(
            new DateObject(
                'yesterday',
            ),
            DateObject::yesterday(),
        );
    }

    /**
    *   @test
    */
    public function tomorrow()
    {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEqualsDateTime(
            new DateObject('tomorrow'),
            DateObject::tomorrow(),
        );
    }

    /**
    *   @test
    */
    public function thisYear()
    {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEqualsDateTime(
            new DateObject('this year'),
            DateObject::thisYear(),
        );
    }

    /**
    *   @test
    */
    public function thisMonth()
    {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEqualsDateTime(
            new DateObject('this month'),
            DateObject::thisMonth(),
        );
    }

    public function datetimeToStringProvider()
    {
        return [
            //00:00
            [
                'today',
                (new DateTimeImmutable('today'))
                ->format(
                    DateTimeInterface::ATOM,
                ),
            ],
            //12:34:56
            [
                '2345-12-31T12:34:56',
                (new DateTimeImmutable(
                    '2345-12-31T12:34:56'
                ))->format(
                    DateTimeInterface::ATOM,
                ),
            ],

        ];
    }

    /**
    *   @test
    *   @dataProvider datetimeToStringProvider
    */
    public function datetimeToString(
        string $datetime,
        string $expected_date_string,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = new DateObject($datetime);
        $date = new DateTimeImmutable($datetime);

        $this->assertEquals(
            $expected_date_string,
            $this->callPrivateMethod(
                $obj,
                'datetimeToString',
                [$date],
            ),
        );
    }

    public function addProvider()
    {
        return [
            //year+month
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                new DateIntervalObject('P1Y2M'),
                new DateTimeImmutable('2002-8-23 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider addProvider
    */
    public function add(
        DateTimeImmutable $base_date,
        DateIntervalInterface $interval,
        DateTimeImmutable $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->add($interval);

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function subProvider()
    {
        return [
            //year+month
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                new DateIntervalObject('P1Y2M'),
                new DateTimeImmutable('2000-4-23 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider subProvider
    */
    public function sub(
        DateTimeImmutable $base_date,
        DateIntervalInterface $interval,
        DateTimeImmutable $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->sub($interval);

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function addDurationProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                3,
                'M',
                null,
                new DateObject('2001-9-23 01:23:45'),
            ],
            //time
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                3,
                'H',
                true,
                new DateObject('2001-6-23 04:23:45'),
            ],
            //minus date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                -3,
                'D',
                false,
                new DateObject('2001-6-20 01:23:45'),
            ],
            //minus time
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                -3,
                'S',
                true,
                new DateObject('2001-6-23 01:23:42'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider addDurationProvider
    */
    public function addDuration(
        DateTimeImmutable $base_date,
        int $duration,
        string $designator,
        ?bool $isTime,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $this->callPrivateMethod(
            $actual_date,
            'addDuration',
            [
                $duration,
                $designator,
                $isTime,
            ],
        );

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function subDurationProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                3,
                'M',
                null,
                new DateObject('2001-3-23 01:23:45'),
            ],
            //time
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                3,
                'H',
                true,
                new DateObject('2001-6-22 22:23:45'),
            ],
            //minus date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                -3,
                'D',
                false,
                new DateObject('2001-6-26 01:23:45'),
            ],
            //minus time
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                -3,
                'S',
                true,
                new DateObject('2001-6-23 01:23:48'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider subDurationProvider
    */
    public function subDuration(
        DateTimeImmutable $base_date,
        int $duration,
        string $designator,
        ?bool $isTime,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $this->callPrivateMethod(
            $actual_date,
            'subDuration',
            [
                $duration,
                $designator,
                $isTime,
            ],
        );

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function addYearsProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                3,
                new DateObject('2004-6-23 01:23:45'),
            ],
            //minus date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                -3,
                new DateObject('1998-6-23 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider addYearsProvider
    */
    public function addYears(
        DateTimeImmutable $base_date,
        int $duration,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->addYears(
            $duration,
        );

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function addMonthsProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                3,
                new DateObject('2001-9-23 01:23:45'),
            ],
            //minus date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                -3,
                new DateObject('2001-3-23 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider addMonthsProvider
    */
    public function addMonths(
        DateTimeImmutable $base_date,
        int $duration,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->addMonths(
            $duration,
        );

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function addWeeksProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                3,
                new DateObject('2001-7-14 01:23:45'),
            ],
            //minus date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                -3,
                new DateObject('2001-6-2 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider addWeeksProvider
    */
    public function addWeeks(
        DateTimeImmutable $base_date,
        int $duration,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->addWeeks(
            $duration,
        );

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function addDaysProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                3,
                new DateObject('2001-6-26 01:23:45'),
            ],
            //minus date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                -3,
                new DateObject('2001-6-20 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider addDaysProvider
    */
    public function addDays(
        DateTimeImmutable $base_date,
        int $duration,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->addDays(
            $duration,
        );

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function addHoursProvider()
    {
        return [
            //time
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                3,
                new DateObject('2001-6-23 04:23:45'),
            ],
            //minus time
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                -3,
                new DateObject('2001-6-22 22:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider addHoursProvider
    */
    public function addHours(
        DateTimeImmutable $base_date,
        int $duration,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->addHours(
            $duration,
        );

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function addMinutesProvider()
    {
        return [
            //time
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                3,
                new DateObject('2001-6-23 01:26:45'),
            ],
            //minus time
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                -3,
                new DateObject('2001-6-23 01:20:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider addMinutesProvider
    */
    public function addMinutes(
        DateTimeImmutable $base_date,
        int $duration,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->addMinutes(
            $duration,
        );

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function addSecondsProvider()
    {
        return [
            //time
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                3,
                new DateObject('2001-6-23 01:23:48'),
            ],
            //minus time
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                -3,
                new DateObject('2001-6-23 01:23:42'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider addSecondsProvider
    */
    public function addSeconds(
        DateTimeImmutable $base_date,
        int $duration,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->addSeconds(
            $duration,
        );

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function subYearsProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                3,
                new DateObject('1998-6-23 01:23:45'),
            ],
            //minus date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                -3,
                new DateObject('2004-6-23 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider subYearsProvider
    */
    public function subYears(
        DateTimeImmutable $base_date,
        int $duration,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->subYears(
            $duration,
        );

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function subMonthsProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                3,
                new DateObject('2001-3-23 01:23:45'),
            ],
            //minus date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                -3,
                new DateObject('2001-9-23 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider subMonthsProvider
    */
    public function subMonths(
        DateTimeImmutable $base_date,
        int $duration,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->subMonths(
            $duration,
        );

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function subWeeksProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                3,
                new DateObject('2001-6-2 01:23:45'),
            ],
            //minus date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                -3,
                new DateObject('2001-7-14 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider subWeeksProvider
    */
    public function subWeeks(
        DateTimeImmutable $base_date,
        int $duration,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->subWeeks(
            $duration,
        );

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function subDaysProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                3,
                new DateObject('2001-6-20 01:23:45'),
            ],
            //minus date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                -3,
                new DateObject('2001-6-26 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider subDaysProvider
    */
    public function subDays(
        DateTimeImmutable $base_date,
        int $duration,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->subDays(
            $duration,
        );

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function subHoursProvider()
    {
        return [
            //time
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                3,
                new DateObject('2001-6-22 22:23:45'),
            ],
            //minus time
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                -3,
                new DateObject('2001-6-23 04:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider subHoursProvider
    */
    public function subHours(
        DateTimeImmutable $base_date,
        int $duration,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->subHours(
            $duration,
        );

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function subMinutesProvider()
    {
        return [
            //time
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                3,
                new DateObject('2001-6-23 01:20:45'),
            ],
            //minus time
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                -3,
                new DateObject('2001-6-23 01:26:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider subMinutesProvider
    */
    public function subMinutes(
        DateTimeImmutable $base_date,
        int $duration,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->subMinutes(
            $duration,
        );

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function subSecondsProvider()
    {
        return [
            //time
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                3,
                new DateObject('2001-6-23 01:23:42'),
            ],
            //minus time
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                -3,
                new DateObject('2001-6-23 01:23:48'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider subSecondsProvider
    */
    public function subSeconds(
        DateTimeImmutable $base_date,
        int $duration,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->subSeconds(
            $duration,
        );

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function nextYearProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                new DateObject('2002-6-23 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider nextYearProvider
    */
    public function nextYear(
        DateTimeImmutable $base_date,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->nextYear();

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function nextMonthProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                new DateObject('2001-7-23 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider nextMonthProvider
    */
    public function nextMonth(
        DateTimeImmutable $base_date,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->nextMonth();

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function nextWeekProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                new DateObject('2001-6-30 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider nextWeekProvider
    */
    public function nextWeek(
        DateTimeImmutable $base_date,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->nextWeek();

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function nextDayProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                new DateObject('2001-6-24 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider nextDayProvider
    */
    public function nextDay(
        DateTimeImmutable $base_date,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->nextDay();

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function previousYearProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                new DateObject('2000-6-23 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider previousYearProvider
    */
    public function previousYear(
        DateTimeImmutable $base_date,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->previousYear();

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function previousMonthProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                new DateObject('2001-5-23 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider previousMonthProvider
    */
    public function previousMonth(
        DateTimeImmutable $base_date,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->previousMonth();

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function previousWeekProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                new DateObject('2001-6-16 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider previousWeekProvider
    */
    public function previousWeek(
        DateTimeImmutable $base_date,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->previousWeek();

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function previousDayProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                new DateObject('2001-6-22 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider previousDayProvider
    */
    public function previousDay(
        DateTimeImmutable $base_date,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->previousDay();

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function modifyProvider()
    {
        return [
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                '+1 year',
                new DateObject('2002-6-23 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider modifyProvider
    */
    public function modify(
        DateTimeImmutable $base_date,
        string $modifier,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->modify($modifier);

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function firstDayOfYearProvider()
    {
        return [
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                new DateObject('2001-1-1 00:00:00'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider firstDayOfYearProvider
    */
    public function firstDayOfYear(
        DateTimeImmutable $base_date,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->firstDayOfYear();

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function firstDayOfMonthProvider()
    {
        return [
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                new DateObject('2001-6-1 00:00:00'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider firstDayOfMonthProvider
    */
    public function firstDayOfMonth(
        DateTimeImmutable $base_date,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->firstDayOfMonth();

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function lastDayOfYearProvider()
    {
        return [
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                new DateObject('2001-12-31 00:00:00'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider lastDayOfYearProvider
    */
    public function lastDayOfYear(
        DateTimeImmutable $base_date,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->lastDayOfYear();

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function lastDayOfMonthProvider()
    {
        return [
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                new DateObject('2001-6-30 00:00:00'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider lastDayOfMonthProvider
    */
    public function lastDayOfMonth(
        DateTimeImmutable $base_date,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->lastDayOfMonth();

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function sameObjectProvider()
    {
        return [
            [
                ($date = new DateObject('2001-6-23 01:23:45')),
                $date,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider sameObjectProvider
    */
    public function sameObject(
        DateInterface $src_date,
        DateInterface $dest_date,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertTrue(
            $src_date->same($dest_date),
        );

        $this->assertFalse(
            $src_date->different($dest_date),
        );

        $this->assertTrue(
            $src_date->eq($dest_date),
        );

        $this->assertFalse(
            $src_date->ne($dest_date),
        );
    }

    public function differentObjectProvider()
    {
        return [
            [
                ($date = new DateObject('2001-6-23 01:23:45')),
                clone $date,
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider differentObjectProvider
    */
    public function differentObject(
        DateInterface $src_date,
        DateInterface $dest_date,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertFalse(
            $src_date->same($dest_date),
        );

        $this->assertTrue(
            $src_date->different($dest_date),
        );

        $this->assertTrue(
            $src_date->eq($dest_date),
        );

        $this->assertFalse(
            $src_date->ne($dest_date),
        );
    }

    public function equalObjectProvider()
    {
        return [
            [
                ($date = new DateObject('2001-6-23 01:23:45')),
                clone $date,
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider equalObjectProvider
    */
    public function equalObject(
        DateInterface $src_date,
        DateInterface $dest_date,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertFalse(
            $src_date->same($dest_date),
        );

        $this->assertTrue(
            $src_date->different($dest_date),
        );

        $this->assertTrue(
            $src_date->eq($dest_date),
        );

        $this->assertFalse(
            $src_date->ne($dest_date),
        );
    }

    public function notEqualObjectProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2011-6-23 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider notEqualObjectProvider
    */
    public function notEqualObject(
        DateInterface $src_date,
        DateInterface $dest_date,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertFalse(
            $src_date->same($dest_date),
        );

        $this->assertTrue(
            $src_date->different($dest_date),
        );

        $this->assertFalse(
            $src_date->eq($dest_date),
        );

        $this->assertTrue(
            $src_date->ne($dest_date),
        );
    }

    public function greaterThanObjectProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 01:23:44'),
                true,
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 01:23:45'),
                false,
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 01:23:46'),
                false,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider greaterThanObjectProvider
    */
    public function greaterThanObject(
        DateInterface $src_date,
        DateInterface $dest_date,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $src_date->gt($dest_date),
        );
    }

    public function greaterEqualObjectProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 01:23:44'),
                true,
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 01:23:45'),
                true,
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 01:23:46'),
                false,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider greaterEqualObjectProvider
    */
    public function greaterEqualObject(
        DateInterface $src_date,
        DateInterface $dest_date,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $src_date->ge($dest_date),
        );
    }

    public function lessThanObjectProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 01:23:44'),
                false,
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 01:23:45'),
                false,
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 01:23:46'),
                true,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider lessThanObjectProvider
    */
    public function lessThanObject(
        DateInterface $src_date,
        DateInterface $dest_date,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $src_date->lt($dest_date),
        );
    }

    public function lessEqualObjectProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 01:23:44'),
                false,
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 01:23:45'),
                true,
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 01:23:46'),
                true,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider lessEqualObjectProvider
    */
    public function lessEqualObject(
        DateInterface $src_date,
        DateInterface $dest_date,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $src_date->le($dest_date),
        );
    }

    public function betweenProvider()
    {
        return [
            //date = start
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 02:23:45'),
                true,
            ],
            //date = end
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 00:23:45'),
                new DateObject('2001-6-23 01:23:45'),
                true,
            ],
            //start < date < end
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 00:23:45'),
                new DateObject('2001-6-23 02:23:45'),
                true,
            ],
            //date < start
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 01:23:46'),
                new DateObject('2001-6-23 02:23:45'),
                false,
            ],
            //date > end
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 00:23:45'),
                new DateObject('2001-6-23 01:23:44'),
                false,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider betweenProvider
    */
    public function between(
        DateInterface $date,
        DateInterface $start_date,
        DateInterface $end_date,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $date->between(
                $start_date,
                $end_date,
            ),
        );
    }

    public function containProvider()
    {
        return [
            //date = start
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 02:23:45'),
                false,
            ],
            //date = end
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 00:23:45'),
                new DateObject('2001-6-23 01:23:45'),
                false,
            ],
            //start < date < end
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 00:23:45'),
                new DateObject('2001-6-23 02:23:45'),
                true,
            ],
            //date < start
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 01:23:46'),
                new DateObject('2001-6-23 02:23:45'),
                false,
            ],
            //date > end
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 00:23:45'),
                new DateObject('2001-6-23 01:23:44'),
                false,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider containProvider
    */
    public function contain(
        DateInterface $date,
        DateInterface $start_date,
        DateInterface $end_date,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $date->contain(
                $start_date,
                $end_date,
            ),
        );
    }

    public function overlapProvider()
    {
        return [
            //date = start
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 02:23:45'),
                true,
            ],
            //date = end
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 00:23:45'),
                new DateObject('2001-6-23 01:23:45'),
                false,
            ],
            //start < date < end
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 00:23:45'),
                new DateObject('2001-6-23 02:23:45'),
                true,
            ],
            //date < start
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 01:23:46'),
                new DateObject('2001-6-23 02:23:45'),
                false,
            ],
            //date > end
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 00:23:45'),
                new DateObject('2001-6-23 01:23:44'),
                false,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider overlapProvider
    */
    public function overlap(
        DateInterface $date,
        DateInterface $start_date,
        DateInterface $end_date,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $date->overlap(
                $start_date,
                $end_date,
            ),
        );
    }

    public function sameYearProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-1-1 00:00:00'),
                true,
            ],
            [
                new DateObject('2001-5-23 01:23:45'),
                new DateObject('2001-12-31 23:59:59'),
                true,
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2000-12-31 23:59:59'),
                false,
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2002-1-1 00:00:00'),
                false,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider sameYearProvider
    */
    public function sameYear(
        DateInterface $base_date,
        DateInterface $target_date,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $base_date->sameYear($target_date),
        );
    }

    public function sameMonthProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-1 00:00:00'),
                true,
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-30 23:59:59'),
                true,
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-5-31 23:59:59'),
                false,
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-7-1 00:00:00'),
                false,
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2002-6-23 01:23:45'),
                false,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider sameMonthProvider
    */
    public function sameMonth(
        DateInterface $base_date,
        DateInterface $target_date,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $base_date->sameMonth($target_date),
        );
    }

    public function sameDayProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 00:00:00'),
                true,
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-23 23:59:59'),
                true,
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-22 23:59:59'),
                false,
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2001-6-24 00:00:00'),
                false,
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2002-6-23 01:23:45'),
                false,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider sameDayProvider
    */
    public function sameDay(
        DateInterface $base_date,
        DateInterface $target_date,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $base_date->sameDay($target_date),
        );
    }






    public function toArrayProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45'),
                getDate(
                    mktime(1, 23, 45, 6, 23, 1),
                ),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider toArrayProvider
    */
    public function toArray(
        DateInterface $date,
        array $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $date->toArray(),
        );
    }

    public function yearProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45'),
                2001,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider yearProvider
    */
    public function year(
        DateInterface $date,
        int $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $date->year(),
        );
    }

    public function monthProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45'),
                6,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider monthProvider
    */
    public function month(
        DateInterface $date,
        int $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $date->month(),
        );
    }

    public function weekProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45'),
                6,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider weekProvider
    */
    public function week(
        DateInterface $date,
        int $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $date->week(),
        );
    }

    public function dayProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45'),
                23,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider dayProvider
    */
    public function day(
        DateInterface $date,
        int $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $date->day(),
        );
    }

    public function hourProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45'),
                1,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider hourProvider
    */
    public function hour(
        DateInterface $date,
        int $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $date->hour(),
        );
    }

    public function minuteProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45'),
                23,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider minuteProvider
    */
    public function minute(
        DateInterface $date,
        int $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $date->minute(),
        );
    }

    public function secondProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45'),
                45,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider secondProvider
    */
    public function second(
        DateInterface $date,
        int $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $date->second(),
        );
    }

    public function microsecondProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45.123456'),
                123456,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider microsecondProvider
    */
    public function microsecond(
        DateInterface $date,
        int $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $date->microsecond(),
        );
    }

    public function timezoneProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateTimeZoneObject(
                    date_default_timezone_get(),
                ),
            ],
            [
                new DateObject(
                    '2001-6-23 01:23:45.123456',
                    ($timezone = new DateTimeZoneObject('UTC')),
                ),
                $timezone,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider timezoneProvider
    */
    public function timezone(
        DateInterface $date,
        DateTimeZoneInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $date->timezone(),
        );
    }

    public function unixtimeProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45'),
                mktime(1, 23, 45, 6, 23, 1),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider unixtimeProvider
    */
    public function unixtime(
        DateInterface $date,
        int $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $date->unixtime(),
        );
    }

    public function exceptProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2002-6-23 01:23:45'),
                false,
                new DateInterval('P1Y'),
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2000-6-23 01:23:45'),
                false,
                new DateInterval('P1Y'),
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateObject('2000-6-23 01:23:45'),
                true,
                new DateInterval('P1Y'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider exceptProvider
    */
    public function except(
        DateInterface $date,
        DateInterface $targetObject,
        bool $absolute = false,
        DateInterval $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $interval = $date->except(
            $targetObject,
            $absolute,
        );

        $actual = $interval->toDateInterval();

        $this->assertEquals(
            $expect->format('%Y-%M-%D %H:%I-%S.%F'),
            $actual->format('%Y-%M-%D %H:%I-%S.%F'),
        );
    }

    public function diffProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateTimeImmutable('2002-6-23 01:23:45'),
                false,
                (new DateObject('2001-6-23 01:23:45'))
                    ->diff(
                        new DateTimeImmutable('2002-6-23 01:23:45'),
                        false,
                    )
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateTimeImmutable('2000-6-23 01:23:45'),
                false,
                (new DateObject('2001-6-23 01:23:45'))
                    ->diff(
                        new DateTimeImmutable('2000-6-23 01:23:45'),
                        false,
                    )
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                new DateTimeImmutable('2000-6-23 01:23:45'),
                true,
                (new DateObject('2001-6-23 01:23:45'))
                    ->diff(
                        new DateTimeImmutable('2000-6-23 01:23:45'),
                        true,
                    )
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider diffProvider
    */
    public function diff(
        DateInterface $date,
        DateTimeInterface $targetObject,
        bool $absolute = false,
        DateInterval $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual = $date->diff(
            $targetObject,
            $absolute,
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public function getOffsetProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45'),
                (new DateTimeImmutable('2001-6-23 01:23:45'))
                    ->getOffset(),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider getOffsetProvider
    */
    public function getOffset(
        DateInterface $date,
        int $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $date->getOffset(),
        );
    }

    public function getTimestampProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45'),
                (new DateTimeImmutable('2001-6-23 01:23:45'))
                    ->getTimestamp(),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider getTimestampProvider
    */
    public function getTimestamp(
        DateInterface $date,
        int $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $date->getTimestamp(),
        );
    }

    public function getTimezoneProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45'),
                (new DateTimeImmutable('2001-6-23 01:23:45'))
                    ->getTimezone(),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider getTimezoneProvider
    */
    public function getTimezone(
        DateInterface $date,
        DateTimeZone $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $date->getTimezone(),
        );
    }

    //half quater test

    public function setFiscalStartMonthProvider()
    {
        $date = new DateObject('2001-6-23 01:23:45');

        return [
            [
                $date,
                null,
                (function () use ($date) {
                    $this->setPrivateProperty(
                        $date,
                        'fiscal_start_month',
                        4,
                    );
                    return $date;
                })(),
            ],
            [
                $date,
                1,
                (function () use ($date) {
                    $this->setPrivateProperty(
                        $date,
                        'fiscal_start_month',
                        1,
                    );
                    return $date;
                })(),
            ],
            [
                $date,
                12,
                (function () use ($date) {
                    $this->setPrivateProperty(
                        $date,
                        'fiscal_start_month',
                        12,
                    );
                    return $date;
                })(),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider setFiscalStartMonthProvider
    */
    public function setFiscalStartMonth(
        DateObject $base_date,
        ?int $month,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $base_date->setFiscalStartMonth($month),
        );
    }

    /**
    *   @test
    */
    public function setFiscalStartMonthException()
    {
        //$this->markTestIncomplete('---markTestIncomplete---');

        try {
            $date = DateObject::today();
            $date->setFiscalStartMonth(-1);
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(
                "required 1 to 12",
                $e->getMessage(),
            );
            return;
        }
        $this->assertTrue(false);
    }

    public function toHalfProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45', null, 4),
                5,
                new DateObject('2001-5-1 00:00:00', null, 5),
            ],
             [
                new DateObject('2001-6-23 01:23:45', null, 4),
                10,
                new DateObject('2001-4-1 00:00:00', null, 10),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider toHalfProvider
    */
    public function toHalf(
        DateInterface $datetime,
        ?int $fiscal_start_month,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual = $this->callPrivateMethod(
            $datetime,
            'toHalf',
            [
                $datetime,
                $fiscal_start_month,
            ],
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public function thisHalfProvider()
    {
        return [
            //default fiscal_start_month
            [
                null,
                new DateObject('2001-4-1 00:00:00', null, 4),
            ],
            //today > fiscal_start_month
            [
                5,
                new DateObject('2001-5-1 00:00:00', null, 5),
            ],
            //today < fiscal_start_month
            [
                7,
                new DateObject('2001-1-1 00:00:00', null, 7),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider thisHalfProvider
    */
    public function thisHalf(
        ?int $set_fiscal_start_month,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual = TestDateObjectStub1::thisHalf(
            $set_fiscal_start_month,
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public function toQuaterProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45', null, 4),
                5,
                new DateObject('2001-5-1 00:00:00', null, 5),
            ],
            [
                new DateObject('2001-6-23 01:23:45', null, 4),
                10,
                new DateObject('2001-4-1 00:00:00', null, 10),
            ],
            [
                new DateObject('2001-6-23 01:23:45', null, 4),
                8,
                new DateObject('2001-5-1 00:00:00', null, 8),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider toQuaterProvider
    */
    public function toQuater(
        DateInterface $datetime,
        ?int $fiscal_start_month,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual = $this->callPrivateMethod(
            $datetime,
            'toQuater',
            [
                $datetime,
                $fiscal_start_month,
            ],
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }







    public function thisQuaterProvider()
    {
        return [
            //default fiscal_start_month
            [
                null,
                new DateObject('2001-4-1 00:00:00', null, 4),
            ],
            //today 1Q
            [
                5,
                new DateObject('2001-5-1 00:00:00', null, 5),
            ],
            //today 2Q
            [
                3,
                new DateObject('2001-6-1 00:00:00', null, 3),
            ],
             //today 3Q
            [
                12,
                new DateObject('2001-6-1 00:00:00', null, 12),
            ],
             //today 4Q
            [
                9,
                new DateObject('2001-6-1 00:00:00', null, 9),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider thisQuaterProvider
    */
    public function thisQuater(
        ?int $set_fiscal_start_month,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual = TestDateObjectStub1::thisQuater(
            $set_fiscal_start_month,
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public function addHalfsProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                3,
                new DateObject('2002-12-23 01:23:45'),
            ],
            //minus date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                -3,
                new DateObject('1999-12-23 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider addHalfsProvider
    */
    public function addHalfs(
        DateTimeImmutable $base_date,
        int $duration,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->addHalfs(
            $duration,
        );

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function addQuatersProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                3,
                new DateObject('2002-3-23 01:23:45'),
            ],
            //minus date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                -3,
                new DateObject('2000-9-23 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider addQuatersProvider
    */
    public function addQuaters(
        DateTimeImmutable $base_date,
        int $duration,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->addQuaters(
            $duration,
        );

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }




    public function subHalfsProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                3,
                new DateObject('1999-12-23 01:23:45'),
            ],
            //minus date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                -3,
                new DateObject('2002-12-23 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider subHalfsProvider
    */
    public function subHalfs(
        DateTimeImmutable $base_date,
        int $duration,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->subHalfs(
            $duration,
        );

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function subQuatersProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                3,
                new DateObject('2000-9-23 01:23:45'),
            ],
            //minus date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                -3,
                new DateObject('2002-3-23 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider subQuatersProvider
    */
    public function subQuaters(
        DateTimeImmutable $base_date,
        int $duration,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->subQuaters(
            $duration,
        );

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function nextHalfProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                new DateObject('2001-12-23 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider nextHalfProvider
    */
    public function nextHalf(
        DateTimeImmutable $base_date,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->nextHalf();

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function nextQuaterProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                new DateObject('2001-9-23 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider nextQuaterProvider
    */
    public function nextQuater(
        DateTimeImmutable $base_date,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->nextQuater();

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function previousHalfProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                new DateObject('2000-12-23 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider previousHalfProvider
    */
    public function previousHalf(
        DateTimeImmutable $base_date,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->previousHalf();

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function previousQuaterProvider()
    {
        return [
            //date
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                new DateObject('2001-3-23 01:23:45'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider previousQuaterProvider
    */
    public function previousQuater(
        DateTimeImmutable $base_date,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $actual_date->previousQuater();

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function createDayOfProvider()
    {
        return [
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                'first',
                'january',
                new DateObject('2001-1-1 00:00:00'),
            ],
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                'last',
                'may',
                new DateObject('2001-5-31 00:00:00'),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider createDayOfProvider
    */
    public function createDayOf(
        DateTimeImmutable $base_date,
        string $ordinal,
        string $month_string,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        );

        $actual = $this->callPrivateMethod(
            $actual_date,
            'createDayOf',
            [
                $ordinal,
                $month_string,
            ],
        );

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function monthStringDayOfHalfProvider()
    {
        return [
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                null,
                'April',
            ],
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                1,
                'January',
            ],
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                12,
                'June',
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider monthStringDayOfHalfProvider
    */
    public function monthStringDayOfHalf(
        DateTimeImmutable $base_date,
        ?int $fiscal_start_month,
        string $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        )->setFiscalStartMonth($fiscal_start_month);

        $actual = $this->callPrivateMethod(
            $actual_date,
            'monthStringDayOfHalf',
            [],
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public function monthStringDayOfQuaterProvider()
    {
        return [
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                null,
                'April',
            ],
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                5,
                'May',
            ],
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                2,
                'May',
            ],
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                10,
                'April',
            ],
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                12,
                'June',
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider monthStringDayOfQuaterProvider
    */
    public function monthStringDayOfQuater(
        DateTimeImmutable $base_date,
        ?int $fiscal_start_month,
        string $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        )->setFiscalStartMonth($fiscal_start_month);

        $actual = $this->callPrivateMethod(
            $actual_date,
            'monthStringDayOfQuater',
            [],
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public function firstDayOfHalfProvider()
    {
        return [
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                3,
                new DateObject('2001-3-1 00:00:00'),
            ],
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                11,
                new DateObject('2001-5-1 00:00:00'),
            ],
         ];
    }

    /**
    *   @test
    *   @dataProvider firstDayOfHalfProvider
    */
    public function firstDayOfHalf(
        DateTimeImmutable $base_date,
        int $fiscal_start_month,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        )->setFiscalStartMonth($fiscal_start_month);

        $actual = $actual_date->firstDayOfHalf();

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function firstDayOfQuaterProvider()
    {
        return [
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                5,
                new DateObject('2001-5-1 00:00:00'),
            ],
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                2,
                new DateObject('2001-5-1 00:00:00'),
            ],
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                7,
                new DateObject('2001-4-1 00:00:00'),
            ],
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                11,
                new DateObject('2001-5-1 00:00:00'),
            ],
         ];
    }

    /**
    *   @test
    *   @dataProvider firstDayOfQuaterProvider
    */
    public function firstDayOfQuater(
        DateTimeImmutable $base_date,
        int $fiscal_start_month,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        )->setFiscalStartMonth($fiscal_start_month);

        $actual = $actual_date->firstDayOfQuater();

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function lastDayOfHalfProvider()
    {
        return [
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                3,
                new DateObject('2001-8-31 00:00:00'),
            ],
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                11,
                new DateObject('2001-10-31 00:00:00'),
            ],
         ];
    }

    /**
    *   @test
    *   @dataProvider lastDayOfHalfProvider
    */
    public function lastDayOfHalf(
        DateTimeImmutable $base_date,
        int $fiscal_start_month,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        )->setFiscalStartMonth($fiscal_start_month);

        $actual = $actual_date->lastDayOfHalf();

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function lastDayOfQuaterProvider()
    {
        return [
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                5,
                new DateObject('2001-7-31 00:00:00'),
            ],
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                2,
                new DateObject('2001-7-31 00:00:00'),
            ],
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                7,
                new DateObject('2001-6-30 00:00:00'),
            ],
            [
                new DateTimeImmutable('2001-6-23 01:23:45'),
                11,
                new DateObject('2001-7-31 00:00:00'),
            ],
         ];
    }

    /**
    *   @test
    *   @dataProvider lastDayOfQuaterProvider
    */
    public function lastDayOfQuater(
        DateTimeImmutable $base_date,
        int $fiscal_start_month,
        DateInterface $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual_date = DateObject::createFromInterface(
            $base_date,
        )->setFiscalStartMonth($fiscal_start_month);

        $actual = $actual_date->lastDayOfQuater();

        $this->assertEquals(
            $expect->format(DateTimeInterface::ATOM),
            $actual->format(DateTimeInterface::ATOM),
        );
    }

    public function halfProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45'),
                4,
                0,
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                10,
                1,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider halfProvider
    */
    public function half(
        DateInterface $date,
        int $fiscal_start_month,
        int $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual = $date->setFiscalStartMonth(
            $fiscal_start_month,
        );

        $this->assertEquals(
            $expect,
            $actual->half(),
        );
    }

    public function quaterProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45'),
                4,
                0,
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                1,
                1,
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                11,
                2,
            ],
            [
                new DateObject('2001-6-23 01:23:45'),
                8,
                3,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider quaterProvider
    */
    public function quater(
        DateInterface $date,
        int $fiscal_start_month,
        int $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $actual = $date->setFiscalStartMonth(
            $fiscal_start_month,
        );

        $this->assertEquals(
            $expect,
            $actual->quater(),
        );
    }

    public function sameHalfProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45', null, 4),
                new DateObject('2001-4-1 00:00:00', null, 4),
                true,
            ],
            [
                new DateObject('2001-5-23 01:23:45', null, 4),
                new DateObject('2001-9-30 23:59:59', null, 4),
                true,
            ],
            [
                new DateObject('2001-6-23 01:23:45', null, 4),
                new DateObject('2001-10-1 00:00:00', null, 4),
                false,
            ],
            [
                new DateObject('2001-6-23 01:23:45', null, 4),
                new DateObject('2001-3-31 23:59:59', null, 4),
                false,
            ],
            [
                new DateObject('2002-1-23 01:23:45', null, 12),
                new DateObject('2001-12-1 01:23:45', null, 12),
                true,
            ],
            [
                new DateObject('2001-2-23 01:23:45', null, 12),
                new DateObject('2001-1-15 01:23:45', null, 12),
                true,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider sameHalfProvider
    */
    public function sameHalf(
        DateInterface $base_date,
        DateInterface $target_date,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $base_date->sameHalf($target_date),
        );
    }

    public function sameQuaterProvider()
    {
        return [
            [
                new DateObject('2001-6-23 01:23:45', null, 4),
                new DateObject('2001-4-1 00:00:00', null, 4),
                true,
            ],
            [
                new DateObject('2001-6-23 01:23:45', null, 4),
                new DateObject('2001-6-30 23:59:59', null, 4),
                true,
            ],
            [
                new DateObject('2001-6-23 01:23:45', null, 4),
                new DateObject('2001-7-1 00:00:00', null, 4),
                false,
            ],
            [
                new DateObject('2001-6-23 01:23:45', null, 4),
                new DateObject('2001-3-31 23:59:59', null, 4),
                false,
            ],
            [
                new DateObject('2001-6-23 01:23:45', null, 12),
                new DateObject('2002-6-23 01:23:45', null, 12),
                false,
            ],
            [
                new DateObject('2001-6-23 01:23:45', null, 12),
                new DateObject('2000-6-13 01:23:45', null, 12),
                false,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider sameQuaterProvider
    */
    public function sameQuater(
        DateInterface $base_date,
        DateInterface $target_date,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $base_date->sameQuater($target_date),
        );
    }
}
