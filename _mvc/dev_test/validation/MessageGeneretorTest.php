<?php

declare(strict_types=1);

namespace test\Concerto\validation;

use test\Concerto\ConcertoTestCase;
use dev\template\CurlyBracketMessageGenerator;
use dev\validation\MessageGenerator;
use dev\validation\Validation;
use dev\validation\constraint\GreaterThen;
use dev\container\ServiceContainer;
use dev\validation\Validator;
use dev\validation\RuleResolver;
use test\Concerto\validation\constraint\TestConstraint1;
use test\Concerto\validation\constraint\TestConstraint2;
use dev\validation\ValidationInterface;

class MessageGeneretorTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function first()
    {
//      $this->markTestIncomplete();

        $obj = new MessageGenerator(new CurlyBracketMessageGenerator());
        $validation = new Validation($obj);
        $validation = $validation->create(
            'prop1',
            10,
            new GreaterThen([11])
        );
        $this->assertEquals('', $obj->generate($validation));

        $message = 'sinple string messafe';
        $obj = $obj->create($message);
        $this->assertEquals($message, $obj->generate($validation));

        $message = 'atr={{attribute}} value={{parameters0}} replace message:{{value}}';
        $actual = 'atr=prop1 value=11 replace message:10';
        $obj = $obj->create($message);
        $this->assertEquals($actual, $obj->generate($validation));
    }
}
