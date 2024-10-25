<?php

declare(strict_types=1);

namespace test\Concerto\event;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Assert;
use Concerto\event\EventManager;
use Concerto\event\EventObject;

////////////////////////////////////////////////////////////

class EventManager2TestEmitter1
{
    public EventManager $manager;

    public function __construct()
    {
        $this->manager = EventManager::create();
    }

    public function update(array $data): array
    {
        $beforeEvent = $this->manager->createEvent(
            'before',
            $data,
        );

        $this->manager->dispatch($beforeEvent);

        sort($data);

        $afterEvent = $this->manager->createEvent(
            'after',
            $data,
        );

        $this->manager->dispatch($afterEvent);

        return $data;
    }
}

class EventManager2TestSubscriber1
{
    public array $expect_before;
    public array $expect_after;
    public int $called_count_before = 0;
    public int $called_count_after = 0;

    public function __construct(
        array $expect_before,
        array $expect_after,
    ) {
        $this->expect_before = $expect_before;
        $this->expect_after = $expect_after;

        $manager = EventManager::create();

        $manager->addListener(
            $manager->buildEventName(
                EventManager2TestEmitter1::class,
                'update',
                'before',
            ),
            [$this, 'onBefore'],
        );

        $manager->addListener(
            $manager->buildEventName(
                EventManager2TestEmitter1::class,
                'update',
                'after',
            ),
            [$this, 'onAfter'],
        );
    }

    public function onBefore(object $event): void
    {
        $this->called_count_before++;

        Assert::assertEquals(
            $this->expect_before,
            $event->getEventData(),
            "faild=" . __METHOD__,
        );
    }

    public function onAfter(EventObject $event): void
    {
        $this->called_count_after++;

        Assert::assertEquals(
            $this->expect_after,
            $event->getEventData(),
            "faild=" . __METHOD__,
        );
    }
}

////////////////////////////////////////////////////////////

class EventManager2Test extends ConcertoTestCase
{
    #[Test]
    public function main()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $emitter = new EventManager2TestEmitter1();

        $expect_after = range(1, 10);

        $expect_before = $expect_after;
        shuffle($expect_before);

        $subscriber1 = new EventManager2TestSubscriber1(
            $expect_before,
            $expect_after,
        );

        $subscriber2 = new EventManager2TestSubscriber1(
            $expect_before,
            $expect_after,
        );

        $emitterManager = $emitter->manager;

        $emitterProvider = $this->getPrivateProperty(
            $emitterManager,
            'provider',
        );

        $emitter->update($expect_before);

        $this->assertEquals(
            1,
            $subscriber1->called_count_before,
        );

        $this->assertEquals(
            1,
            $subscriber1->called_count_after,
        );

        $this->assertEquals(
            1,
            $subscriber2->called_count_before,
        );

        $this->assertEquals(
            1,
            $subscriber2->called_count_after,
        );
    }
}
