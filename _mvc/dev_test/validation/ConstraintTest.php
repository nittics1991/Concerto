<?php

declare(strict_types=1);

namespace test\Concerto\validation;

use test\Concerto\ConcertoTestCase;
use test\Concerto\validation\constraint\TestConstraint1;
use test\Concerto\validation\constraint\TestConstraint2;
use test\Concerto\validation\constraint\TestConstraint3;

class ConstraintTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function first()
    {
//      $this->markTestIncomplete();

        $obj = new TestConstraint1();

        $obj->setParameters([10]);
        $this->assertEquals(true, $obj->isValid(12));
        $this->assertEquals([10], $obj->getParameters());
    }

    /**
    *   @test
    */
    public function name()
    {
//      $this->markTestIncomplete();

        $obj = new TestConstraint1();
        $this->assertEquals('TestConstraint1', $obj->name());

        //over write name
        $obj = new TestConstraint2();
        $this->assertEquals('OverWriteNameMethod', $obj->name());
    }

    /**
    *   @test
    */
    public function drowMessage()
    {
//      $this->markTestIncomplete();

        $obj = new TestConstraint1();
        $this->assertEquals('', $obj->message());

        $obj = new TestConstraint3();
        $this->assertEquals(':overWriteMessage', $obj->message());

        $obj = new TestConstraint2();

        $msg = 'OverWriteNameMethod value=0 param=0';
        $this->assertEquals($msg, $obj->message());

        $obj->setParameters([10, 5]);
        $this->assertEquals([10, 5], $obj->getParameters());

        $obj->isValid(8);
        $msg = 'OverWriteNameMethod value=8 param=5';
        $this->assertEquals($msg, $obj->message());

        //set attribute
        $obj = new TestConstraint3();
        $obj->setAttribute('prop1');
        $this->assertEquals('prop1:overWriteMessage', $obj->message());
    }
}
