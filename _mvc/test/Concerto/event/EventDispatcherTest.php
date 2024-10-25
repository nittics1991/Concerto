<?php

declare(strict_types=1);

namespace test\Concerto\event;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\event\EventDispatcher;
use Concerto\event\EventProvider;
use Psr\EventDispatcher\StoppableEventInterface;

////////////////////////////////////////////////////////////
class EventDispatcherTestListener1
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

class EventDispatcherTestEvent1
{
    public int $no;

    public function __construct($no)
    {
        $this->no = $no;
    }
}

class EventDispatcherTestListener2
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

class EventDispatcherTestEvent2 implements
    StoppableEventInterface
{
    public int $no;

    public bool $isStop = false;

    public function __construct($no)
    {
        $this->no = $no;
    }

    public function isPropagationStopped(): bool
    {
        return $this->isStop;
    }
}

////////////////////////////////////////////////////////////

class EventDispatcherTest extends ConcertoTestCase
{
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
                (new EventDispatcherTestEvent1(1))::class,
                new EventDispatcherTestListener1(1),
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
                (new EventDispatcherTestEvent1(4))::class,
                new EventDispatcherTestListener1(4),
                PHP_INT_MIN,
            ],
            //index=5
            [
                (new EventDispatcherTestEvent1(5))::class,
                new EventDispatcherTestListener1(5),
                PHP_INT_MAX,
            ],
            //index=6
            [
                (new EventDispatcherTestEvent1(6))::class,
                new EventDispatcherTestListener1(6),
                PHP_INT_MAX,
            ],
        ];
    }

    #[Test]
    public function dispatch1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $provider = new EventProvider();

        $dataset = $this->getDispatchTestData1();

        $event = new EventDispatcherTestEvent1(0);

        foreach ($dataset as $data) {
            $provider->addListener(
                $event::class,
                $data[1],
                $data[2],
            );
        }

        $obj = new EventDispatcher(
            $provider,
        );

        $this->assertEquals(
            $provider,
            $obj->getProvider(),
        );

        $obj->dispatch($event);

        $actual = EventDispatcherTestListener1::$actuals;

        $expect = [4,1,5,6];

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public function getDispatchTestData2()
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
                (new EventDispatcherTestEvent2(1))::class,
                new EventDispatcherTestListener1(1),
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
                (new EventDispatcherTestEvent2(4))::class,
                new EventDispatcherTestListener1(4),
                PHP_INT_MIN,
            ],
            //index=5
            [
                (new EventDispatcherTestEvent2(5))::class,
                new EventDispatcherTestListener1(5),
                PHP_INT_MAX,
            ],
            //index=6
            [
                (new EventDispatcherTestEvent2(6))::class,
                new EventDispatcherTestListener1(6),
                PHP_INT_MAX,
            ],
        ];
    }

    #[Test]
    public function StoppableEvent()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $provider = new EventProvider();

        $dataset = $this->getDispatchTestData2();

        $event = new EventDispatcherTestEvent2(0);

        foreach ($dataset as $data) {
            $provider->addListener(
                $event::class,
                $data[1],
                $data[2],
            );
        }

        $obj = new EventDispatcher(
            $provider,
        );

        $event->isStop = true;

        $obj->dispatch($event);

        $actual = EventDispatcherTestListener2::$actuals;

        $expect = [];

        $this->assertEquals(
            $expect,
            $actual,
        );
    }
}
