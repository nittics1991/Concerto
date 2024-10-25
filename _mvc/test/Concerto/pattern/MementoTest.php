<?php

declare(strict_types=1);

namespace test\Concerto\pattern;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\pattern\Memento;

class StubMemento extends Memento
{
    public function __construct($params)
    {
        parent::__construct($params);
    }

    public function getMemento(): mixed
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
    *
    */
    #[Test]
    public function getCalledClassName()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $object = new StubMemento('DUMMY');
        $actual = $this->callPrivateMethod($object, 'getCalledClassName');
        $this->assertEquals(__CLASS__, $actual);
    }

    /**
    *
    */
    #[Test]
    public function getMemento()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $expect = 'DUMMY';
        $object = new StubMemento($expect);
        $this->assertEquals($expect, $object->getMemento());
    }
}
