<?php

declare(strict_types=1);

namespace Concerto\test\pattern;

use Concerto\pattern\ObjectStorageSubject;
use Concerto\test\ConcertoTestCase;
use SplObserver;
use SplSubject;

class Observer implements SplObserver
{
    public function update(SplSubject $subject)
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
    **/
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
        
        $actual = array();
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
    **/
    public function notify()
    {
//      $this->markTestIncomplete();
        
        $mock = $this->getMockBuilder('Concerto\test\pattern\Observer')
            ->setMethods(['update'])
            ->getMock();
        
        $mock->method("update")
            ->will($this->onConsecutiveCalls(0, 1, 2));
        
        $observer = $mock;
        
        $object = new ObjectStorageSubject();
        $object->attach(clone $observer);
        $object->attach(clone $observer);
        $object->attach(clone $observer);
        $actual = $object->notify();
        $this->assertEquals(array(0, 1, 2), $actual);
    }
}
