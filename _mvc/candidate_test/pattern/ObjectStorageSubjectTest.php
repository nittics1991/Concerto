<?php

declare(strict_types=1);

namespace candidate_test\pattern;

use candidate\pattern\ObjectStorageSubject;
use test\Concerto\ConcertoTestCase;
use SplObserver;
use SplSubject;

class Observer implements SplObserver
{
    public function update(SplSubject $subject): void
    {
    }
}


class ObjectStorageSubjectTest extends ConcertoTestCase
{
    protected function setUp(): void
    {
    }

    /**
    *   基本処理確認
    *
    *   @test
    */
    public function basic()
    {
//      $this->markTestIncomplete();

        $observer = new Observer();
        $observers[] = clone $observer;
        $observers[] = clone $observer;
        $observers[] = clone $observer;

        $object = new ObjectStorageSubject();
        $object->attach($observers[0]);
        $object->attach($observers[1]);
        $object->attach($observers[2]);
        $object->detach($observers[1]);

        $storage = $this->getPrivateProperty($object, 'storage');
        $this->assertEquals(2, $storage->count());

        $actual = [];
        foreach ($storage as $obj) {
            if (($key = array_search($obj, $observers, true)) !== false) {
                $actual[] = $key;
            }
        }

        $this->assertEquals(array(0, 2), $actual);

        $expect = $observers;
        array_splice($expect, 1, 1);
        $this->assertEquals($expect, $object->toArray());

        $object = new ObjectStorageSubject();
        $object->fromArray($observers);
        $this->assertEquals($observers, $object->toArray());
    }

    /**
    *   notify動作確認
    *
    *   @test
    */
    public function notify()
    {
     $this->markTestIncomplete('---notify not return test---');

        $mock = $this->getMockBuilder(Observer::class)
            ->setMethods(['update'])
            ->getMock();

        $mock->method("update")
            ->will($this->onConsecutiveCalls(0, 1, 2));

        $observer = $mock;

        $object = new ObjectStorageSubject();
        $object->attach(clone $observer);
        $object->attach(clone $observer);
        $object->attach(clone $observer);
        $object->notify();
    }
}
