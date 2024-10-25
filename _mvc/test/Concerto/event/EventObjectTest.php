<?php

declare(strict_types=1);

namespace test\Concerto\event;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\event\EventObject;

class EventObjectTest extends ConcertoTestCase
{
    public static function constructProvider()
    {
        $eventName[0] = 'eventName1';
        $eventData[0] = [1,2,3,4,5];

        return [
            [
                $eventName[0],
                $eventData[0],
            ],
        ];
    }

    #[Test]
    #[DataProvider('constructProvider')]
    public function construct(
        string $eventName,
        mixed $eventData,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new EventObject(
            $eventName,
            $eventData,
        );

        $this->assertEquals(
            $eventName,
            $obj->getEventName(),
        );

        $this->assertEquals(
            $eventData,
            $obj->getEventData(),
        );
    }

    public static function create1Provider()
    {
        $eventName[0] = 'eventName1';
        $eventData[0] = [1,2,3,4,5];

        return [
            [
                $eventName[0],
                $eventData[0],
            ],
        ];
    }

    #[Test]
    #[DataProvider('create1Provider')]
    public function create1(
        string $eventName,
        mixed $eventData,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = EventObject::create(
            $eventName,
            $eventData,
        );

        $this->assertEquals(
            $eventName,
            $obj->getEventName(),
        );

        $this->assertEquals(
            $eventData,
            $obj->getEventData(),
        );
    }
}
