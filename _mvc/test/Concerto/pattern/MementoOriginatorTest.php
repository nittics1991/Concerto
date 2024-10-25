<?php

declare(strict_types=1);

namespace test\Concerto\pattern;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\pattern\Memento;
use Concerto\pattern\MementoOriginator;

class MementoOriginatorTest extends ConcertoTestCase
{
    public function setUp(): void
    {
    }

    /**
    *
    */
    #[Test]
    public function allMethods()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $expect = $data = 'DUMMY';
        $object = new MementoOriginator($data);
        $this->assertEquals($expect, $object->getOriginator());

        $expect = $data = new \ArrayObject([1, 2, 3]);
        $object->setOriginator($data);
        $this->assertEquals($expect, $object->getOriginator());

        $memento = $object->createMemento();
        $this->assertEquals(true, $memento instanceof Memento);

        $object = new MementoOriginator();
        $object->setMemento($memento);
        $this->assertEquals($expect, $object->getOriginator());
    }
}
