<?php

declare(strict_types=1);

namespace Concerto\test\validation;

use Concerto\test\ConcertoTestCase;
use Concerto\template\CurlyBracketMessageGenerator;
use Concerto\validation\MessageGenerator;
use Concerto\validation\Validation;
use Concerto\validation\constraint\GreaterThen;
use Concerto\container\ServiceContainer;
use Concerto\validation\Validator;
use Concerto\validation\RuleResolver;
use Concerto\test\validation\constraint\TestConstraint1;
use Concerto\test\validation\constraint\TestConstraint2;
use Concerto\validation\ValidationInterface;

class MessageGeneratorTest extends ConcertoTestCase
{
    /**
    *   @test
    **/
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
