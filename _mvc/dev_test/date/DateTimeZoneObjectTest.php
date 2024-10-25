<?php

declare(strict_types=1);

namespace test\Concerto\data;

use PHPUnit\Framework\TestCase;
use test\Concerto\{
    ExpansionAssertionTrait,
    PrivateTestTrait,
};
use DateTimeZone;
use Concerto\date\DateTimeZoneObject;

class DateTimeZoneObjectTest extends TestCase
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
                null,
                new DateTimeZone(
                    date_default_timezone_get(),
                ),
            ],
            [
                'UTC',
                new DateTimeZone(
                    'UTC',
                ),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider _constructProvider
    */
    public function _construct(
        ?string $timezone,
        DateTimeZone $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = new DateTimeZoneObject(
            $timezone,
        );

        $this->assertEquals(
            $expect,
            $obj->toDateTimeZone(),
        );
    }

    public function offsetTimeProvider()
    {
        return [
            [
                new DateTimeZoneObject(
                    'Asia/Tokyo',
                ),
                9 * 60 * 60,
            ],
            [
                new DateTimeZoneObject(
                    'UTC',
                ),
                0 * 60 * 60,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider offsetTimeProvider
    */
    public function offsetTime(
        DateTimeZoneObject $timezone,
        int $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $timezone->offsetTime(),
        );
    }

    public function getNameProvider()
    {
        return [
            [
                new DateTimeZoneObject(
                    'Asia/Tokyo',
                ),
                'Asia/Tokyo',
            ],
            [
                new DateTimeZoneObject(
                    'UTC',
                ),
                'UTC',
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider getNameProvider
    */
    public function getName1(
        DateTimeZoneObject $timezone,
        string $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->assertEquals(
            $expect,
            $timezone->getName(),
        );
    }
}
