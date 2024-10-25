<?php

declare(strict_types=1);

namespace test\Concerto\event;

use test\Concerto\ConcertoTestCase;
use candidate\event\Event;

class EventTest extends ConcertoTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
    *   @test
    */
    public function accessor()
    {
//      $this->markTestIncomplete();

        $object = new Event();

        $name = 'myevent';
        $object->setName($name);
        $this->assertEquals($name, $object->getName());

        $target = new \StdClass();
        $object->setTarget($target);
        $this->assertEquals($target, $object->getTarget());

        $params = ['a' => 1, 'b' => 2, 'c' => 3];
        $object->setParams($params);
        $this->assertEquals($params, $object->getParams());
        $this->assertEquals($params['c'], $object->getParam('c'));


        $object2 = new Event($name, $target, $params);
        $this->assertEquals($name, $object2->getName());
        $this->assertEquals($target, $object2->getTarget());
        $this->assertEquals($params, $object2->getParams());
        $this->assertEquals($params['c'], $object2->getParam('c'));
    }

    /**
    *   @test
    */
    public function setNameException()
    {
  //      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('must be type string');
        $object = new Event();
        $name = 12;
        $object->setName($name);
    }

    /**
    *   @test
    */
    public function setTargetException()
    {
  //      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('type missing');
        $object = new Event();
        $target = 12;
        $object->setTarget($target);
    }

    /**
    *   @test
    */
    public function setParamsException()
    {
  //      $this->markTestIncomplete();

        $this->expectException(\TypeError::class);
        $object = new Event();
        $params = 12;
        $object->setParams($params);
    }

    /**
    *   @test
    */
    public function getParamsException()
    {
  //      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('not has parameter:ZZZ');
        $object = new Event();
        $params = ['a' => 1, 'b' => 2, 'c' => 3];
        $object->setParams($params);
        $object->getParam('ZZZ');
    }

    /**
    *   @test
    */
    public function stopPropagation()
    {
  //      $this->markTestIncomplete();

        $object = new Event();

        $propagation = true;
        $object->stopPropagation($propagation);
        $this->assertEquals($propagation, $object->isPropagationStopped());
    }
}
