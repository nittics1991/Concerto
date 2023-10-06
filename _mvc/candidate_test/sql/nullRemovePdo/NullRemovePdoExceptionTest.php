<?php

declare(strict_types=1);

namespace candidate_test\sql\nullRemovePdo;

use test\Concerto\ConcertoTestCase;
use candidate\sql\nullRemovePdo\NullRemovePdoException;
use Exception;
use Throwable;

class NullRemovePdoExceptionTest extends ConcertoTestCase
{
    public function allProvider()
    {
        return [
            //code is int
            [
                'message1',
                1234,
                new Exception(
                    'inner1',
                    9876,
                ),
                'context1'
            ],
            //code is string
            [
                'message2',
                'HY1234',
                new Exception(
                    'inner2',
                    9876,
                ),
                'context2'
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider allProvider
    */
    public function all(
        string $message,
        int|string $code,
        ?Throwable $previous,
        mixed $context,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new NullRemovePdoException(
            $message,
            $code,
            $previous,
            $context,
        );

        $this->assertEquals(
            $message,
            $obj->getMessage(),
        );

        $this->assertEquals(
            true,
            is_int($obj->getCode()),
        );

        $this->assertEquals(
            $previous,
            $obj->getPrevious(),
        );

        $this->assertEquals(
            $context,
            $obj->getContext(),
        );

        //static method
        $created = NullRemovePdoException::create(
            $previous,
            $context,
        );

        $this->assertEquals(
            $previous->getMessage(),
            $created->getMessage(),
        );

        $this->assertEquals(
            $previous->getCode(),
            $created->getCode(),
        );

        $this->assertEquals(
            $context,
            $created->getContext(),
        );
    }
}
