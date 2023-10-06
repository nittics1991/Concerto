<?php

declare(strict_types=1);

namespace test\Concerto\event;

use test\Concerto\ConcertoTestCase;
use candidate\event\Event;
use candidate\event\EventDispatcher;
use candidate\event\EventSubscriberInterface;

class TestSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'event1' => 'func1',
            'event2' => ['func1', 10],
            'event3' => [['func1', 30], ['func2', 20]],
        ];
    }

    public function func1($event)
    {
        $params = $event->getParams();
        return $params[0] - 1;
    }

    public function func2($event)
    {
        $params = $event->getParams();
        return $params[0] - 2;
    }
}

//////////////////////////////////////////////

class EventDispatcherTest extends ConcertoTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
    *   @test
    */
    public function all()
    {
//      $this->markTestIncomplete();

        $object = new EventDispatcher();

        //attach
        $callable[0] = function ($event) {
            return $event->getParam('add') + 1;
        };
        $object->attach('no1', $callable[0], 10);

        $callable[1] = function ($event) {
            return $event->getParam('add') + 2;
        };
        $object->attach('no1', $callable[1], 20);

        $callable[2] = function ($event) {
            return $event->getParam('add') + 3;
        };
        $object->attach('no1', $callable[2], 20);

        $callable[3] = function ($event) {
            return $event->getParam('add') + 4;
        };
        $object->attach('no2', $callable[3], 50);

        $expect = [
            'no1' => [
                10 => [$callable[0]],
                20 => [$callable[1], $callable[2]],
            ],
            'no2' => [
                50 => [$callable[3]]
            ]
        ];

        $actual = $this->getPrivateProperty($object, 'listeners');
        $this->assertEquals($expect, $actual);

        //detach
        $object->detach('no1', $callable[1]);
        unset($expect['no1'][20][0]);

        $actual = $this->getPrivateProperty($object, 'listeners');
        $this->assertEquals($expect, $actual);

        //getListener
        $expect = [
            $callable[0],
            $callable[1]
        ];
        $this->assertEquals($expect, $object->getListeners('no1'));

        //getListenerPriority
        $this->assertEquals(50, $object->getListenerPriority('no2', $callable[3]));

        //hasListeners
        $this->assertEquals(2, $object->hasListeners('no1'));
        $this->assertEquals(0, $object->hasListeners('ZZZ'));

        //trigger & getResult
        $actual = $object->trigger('no1', new \StdClass(), ['add' => 10]);
        $this->assertEquals(11, $actual);
        $this->assertEquals([13, 11], $object->getResults('no1'));

        //claer
        $object->clearListeners('no1');
        $actual = $this->getPrivateProperty($object, 'listeners');
        $this->assertEquals(true, empty($actual['no1']));

        //clear all
        $object->clearListeners();
        $actual = $this->getPrivateProperty($object, 'listeners');
        $this->assertEquals(['' => []], $actual);
    }

    /**
    *   @test
    */
    public function subscriber()
    {
//      $this->markTestIncomplete();

        $object = new EventDispatcher();
        $callable = function ($event) {
            return $event->getParam('add') + 1;
        };
        $object->attach('no1', $callable, 10);

        //add
        $subscriber = new TestSubscriber();
        $object->addSubscriber($subscriber);
        $actual = $object->trigger('event3', $this, [1000]);
        $this->assertEquals(998, $actual);

        $actual = $object->trigger('event2', $this, [1000]);
        $this->assertEquals(999, $actual);

        $actual = $object->trigger('event1', $this, [100]);
        $this->assertEquals(99, $actual);

        //remove
        $object->removeSubscriber($subscriber);

        $this->assertEquals(true, $object->hasListeners('no1'));
        $this->assertEquals(false, $object->hasListeners('event1'));
        $this->assertEquals(false, $object->hasListeners('event2'));
        $this->assertEquals(false, $object->hasListeners('event3'));
    }
}
