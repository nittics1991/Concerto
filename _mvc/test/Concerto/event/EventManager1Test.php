<?php

declare(strict_types=1);

namespace test\Concerto\event;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\event\EventManager;
use Concerto\event\EventProvider;
use Concerto\event\EventDispatcher;

////////////////////////////////////////////////////////////
class EventManager1TestListener1
{
    public static array $actuals = [];

    public int $no;

    public function __construct($no)
    {
        $this->no = $no;
    }

    public function __invoke(...$event)
    {
        static::$actuals[] = $this->no;
    }
}

class EventManager1TestEvent1
{
    public int $no;

    public function __construct($no)
    {
        $this->no = $no;
    }
}

////////////////////////////////////////////////////////////

class EventManager1Test extends ConcertoTestCase
{
    #[Test]
    public function construct()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new EventManager(
            new EventDispatcher(
                new EventProvider(),
            ),
        );

        $this->assertEquals(
            true,
            $this->getPrivateProperty(
                $obj,
                'dispatcher'
            ) instanceof EventDispatcher,
        );

        $this->assertEquals(
            true,
            $this->getPrivateProperty(
                $obj,
                'provider'
            ) instanceof EventProvider,
        );
    }

    #[Test]
    public function createFromDispatcher()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = EventManager::create(
            new EventDispatcher(
                new EventProvider(),
            ),
        );

        $dispatcher = $this->getPrivateProperty(
            $obj,
            'dispatcher',
        );

        $this->assertEquals(
            true,
            $dispatcher instanceof EventDispatcher,
        );

        $provider = $this->getPrivateProperty(
            $obj,
            'provider',
        );

        $this->assertEquals(
            true,
            $provider instanceof EventProvider,
        );

        //same dispatcher
        $obj = EventManager::create();

        $dispatcher2 = $this->getPrivateProperty(
            $obj,
            'dispatcher',
        );

        $provider2 = $this->getPrivateProperty(
            $obj,
            'provider',
        );

        $this->assertEquals(
            $dispatcher,
            $dispatcher2,
        );

        $this->assertEquals(
            $provider,
            $provider2,
        );

        //same dispatcher no used argument EventDispatcher
        $obj = EventManager::create(
            new EventDispatcher(
                new EventProvider(),
            ),
        );

        $dispatcher3 = $this->getPrivateProperty(
            $obj,
            'dispatcher',
        );

        $provider3 = $this->getPrivateProperty(
            $obj,
            'provider',
        );

        $this->assertEquals(
            $dispatcher,
            $dispatcher3,
        );

        $this->assertEquals(
            $provider,
            $provider3,
        );
    }

    public function getDispatchTestData1()
    {
        return [
            //index=0
            [
                'name1',
                fn($o) => "name1-0-1",
                0,
            ],
            //index=1
            [
                (new EventManager1TestEvent1(1))::class,
                new EventManager1TestListener1(1),
                PHP_INT_MAX,
            ],
            //index=2
            [
                'name1',
                fn($o) => "name1-0-2",
                0,
            ],
            //index=3
            [
                'name1',
                fn($o) => "name1-0-3",
                -5,
            ],
            //index=4
            [
                (new EventManager1TestEvent1(4))::class,
                new EventManager1TestListener1(4),
                PHP_INT_MIN,
            ],
            //index=5
            [
                (new EventManager1TestEvent1(5))::class,
                new EventManager1TestListener1(5),
                PHP_INT_MAX,
            ],
            //index=6
            [
                (new EventManager1TestEvent1(6))::class,
                new EventManager1TestListener1(6),
                PHP_INT_MAX,
            ],
        ];
    }

    #[Test]
    public function dispatch1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = EventManager::create();

        $dataset = $this->getDispatchTestData1();

        $event = new EventManager1TestEvent1(0);

        foreach ($dataset as $data) {
            $obj->addListener(
                $event::class,
                $data[1],
                $data[2],
            );
        }

        $obj->dispatch($event);

        $actual = EventManager1TestListener1::$actuals;

        $expect = [4,1,5,6];

        $this->assertEquals(
            $expect,
            $actual,
        );

        //others EventManager
        $obj2 = EventManager::create();

        $obj2->dispatch($event);

        $actual2 = EventManager1TestListener1::$actuals;

        $expect2 = [...$expect, 4,1,5,6];

        $this->assertEquals(
            $expect2,
            $actual2,
        );
    }
}
