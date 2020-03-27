<?php

declare(strict_types=1);

namespace Concerto\test\validation;

use Concerto\test\ConcertoTestCase;
use Concerto\template\CurlyBracketMessageGenerator;
use Concerto\validation\MessageGenerator;
use Concerto\validation\Validation;
use Concerto\test\validation\constraint\TestConstraint1;
use Concerto\test\validation\constraint\TestConstraint2;
use Concerto\test\validation\constraint\TestConstraint3;

class ValidationTest extends ConcertoTestCase
{
    /**
    *   @test
    **/
    public function first()
    {
//      $this->markTestIncomplete();
        
        $attribute = 'name1';
        $value = 12;
        $params = [10];
        $constraint = new TestConstraint1($params);
        
        $obj = new Validation(new MessageGenerator(
            new CurlyBracketMessageGenerator()
        ));
        $obj = $obj->create($attribute, $value, $constraint);
        
        $this->assertEquals($attribute, $obj->attribute());
        $this->assertEquals($value, $obj->value());
        $this->assertEquals($params, $obj->parameters());
        $this->assertEquals('TestConstraint1', $obj->constraint());
        $this->assertEquals('', $obj->message());
    }
    
    /**
    *   @test
    **/
    public function createObject()
    {
//      $this->markTestIncomplete();
        
        $attribute = 'name1';
        $value = 12;
        //$params = [10];
        $constraint = new TestConstraint3();
        
        $obj = new Validation(new MessageGenerator(
            new CurlyBracketMessageGenerator()
        ));
        $obj = $obj->create($attribute, $value, $constraint);
        
        $this->assertEquals($attribute, $obj->attribute());
        $this->assertEquals($value, $obj->value());
        $this->assertEquals([], $obj->parameters());
        $this->assertEquals('TestConstraint3', $obj->constraint());
        $this->assertEquals(':overWriteMessage', $obj->message());
    }
    
    /**
    *   @test
    **/
    public function setMessage()
    {
//      $this->markTestIncomplete();
        
        $attribute = 'name1';
        $value = 12;
        $params = [8, 9];
        $constraint = new TestConstraint2($params);
        $actual = 'OverWriteNameMethod value=12 param=9';
        
        $obj = new Validation(new MessageGenerator(
            new CurlyBracketMessageGenerator()
        ));
        $obj = $obj->create($attribute, $value, $constraint);
        $obj->isValid($value);
        
        $this->assertEquals($attribute, $obj->attribute());
        $this->assertEquals($value, $obj->value());
        $this->assertEquals($params, $obj->parameters());
        $this->assertEquals('OverWriteNameMethod', $obj->constraint());
        $this->assertEquals($actual, $obj->message());
        
        $message = 'attr={{attribute}} constraint={{constraint}} parameter={{parameters1}} value={{value}}';
        $actual = 'attr=name1 constraint=OverWriteNameMethod parameter=9 value=12';
        
        $obj = new Validation(new MessageGenerator(
            new CurlyBracketMessageGenerator()
        ));
        
        $obj = $obj->create($attribute, $value, $constraint, $message);
        $obj->isValid($value);
        
        $this->assertEquals($actual, $obj->message());
    }
}
