<?php

declare(strict_types=1);

namespace test\Concerto\pattern;

use test\Concerto\ConcertoTestCase;
use Concerto\pattern\Memento;

class StubMemento extends Memento
{
    public function __construct($params)
    {
        parent::__construct($params);
    }

    public function getMemento()
    {
        return parent::getMemento();
    }
}

/////////////////////////////////////////////////

class MementoTest extends ConcertoTestCase
{
    public function setUp(): void
    {
    }

    /**
    *   @test
    *
    */
    public function getCalledClassName()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $object = new StubMemento('DUMMY');
        $actual = $this->callPrivateMethod($object, 'getCalledClassName');
        $this->assertEquals(__CLASS__, $actual);
    }

    /**
    *   @test
    *
    */
    public function getMemento()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $expect = 'DUMMY';
        $object = new StubMemento($expect);
        $this->assertEquals($expect, $object->getMemento());
    }
}
