<?php

declare(strict_types=1);

namespace test\Concerto\validation;

use test\Concerto\ConcertoTestCase;
use dev\container\ServiceContainer;
use dev\template\CurlyBracketMessageGenerator;
use dev\validation\MessageGenerator;
use dev\validation\RuleResolver;
use dev\validation\Validation;
use test\Concerto\validation\constraint\TestConstraint1;
use test\Concerto\validation\constraint\TestConstraint2;
use test\Concerto\validation\constraint\TestConstraint3;

////////////////////////////////////////////////////////////////////////////////
function ruleResolverFunction($val, array $inputs)
{
    return $val > 5;
}

class TestCallableConstraint
{
    public static function validate($val, array $inputs)
    {
        return $val < 5;
    }
}

////////////////////////////////////////////////////////////////////////////////

class RuleResolverTest extends ConcertoTestCase
{
    protected function getRuleresolver()
    {
        $container = new ServiceContainer();

        $container->bind('validation.TestConstraint1', function () {
            return new TestConstraint1();
        });

        $container->bind('validation.TestConstraint2', function () {
            return new TestConstraint2();
        });

        $container->bind('validation.TestConstraint3', function () {
            return new TestConstraint3();
        });

        $obj = new RuleResolver(
            $container,
            new Validation(new MessageGenerator(
                new CurlyBracketMessageGenerator()
            ))
        );

        return $obj;
    }

    /**
    *   @test
    */
    public function parseConstraintRule()
    {
//      $this->markTestIncomplete();

        $obj = $this->getRuleresolver();

        $constraint = new TestConstraint1();
        $expect = $this->callPrivateMethod($obj, 'parseConstraintRule', ['prop1', $constraint]);
        $this->assertEquals([$constraint], $expect);
    }

    /**
    *   @test
    */
    public function parseClosureRule()
    {
//      $this->markTestIncomplete();

        $obj = $this->getRuleresolver();

        $constraint = function ($value, $values) {
            if ($values['other'] > 10) {
                return $value > $values['other'];
            }
            return false;
        };

        $dataset = [
            'target' => 3,
            'other' => 13,
        ];

        $generated = $this->callPrivateMethod($obj, 'parseClosureRule', ['prop1',$constraint, $dataset]);
        $this->assertEquals(true, method_exists($generated[0], 'isValid'));
        $this->assertEquals(true, ($generated[0])->isValid(14));
        $this->assertEquals(false, ($generated[0])->isValid(10));

        $constraint = 'class@anonymous';
        $this->assertEquals($constraint, ($generated[0])->name());
        $msg = 'prop1:Closure Constraint:value=10';
        $this->assertEquals($msg, ($generated[0])->message());
        $this->assertEquals([], ($generated[0])->getParameters());
    }

    /**
    *   @test
    */
    public function parseCallableRule()
    {
//      $this->markTestIncomplete();

        $obj = $this->getRuleresolver();

        $dataset = [
            'target' => 3,
            'other' => 13,
        ];

        //function
        $constraint = 'test\Concerto\validation\ruleResolverFunction';
        $generated = $this->callPrivateMethod($obj, 'parseCallableRule', ['prop1', $constraint, $dataset]);
        $this->assertEquals(true, method_exists($generated[0], 'isValid'));
        $this->assertEquals(true, ($generated[0])->isValid(10));

        //callable
        $constraint = [TestCallableConstraint::class, 'validate'];
        $generated = $this->callPrivateMethod($obj, 'parseCallableRule', ['prop1', $constraint, $dataset]);
        $this->assertEquals(true, method_exists($generated[0], 'isValid'));
        $this->assertEquals(false, ($generated[0])->isValid(10));

        $constraint = 'class@anonymous';
        $this->assertEquals($constraint, ($generated[0])->name());
        $msg = 'prop1:Callable Constraint:value=10';
        $this->assertEquals($msg, ($generated[0])->message());
        $this->assertEquals([], ($generated[0])->getParameters());
    }

    /**
    *   @test
    */
    public function buildRule()
    {
//      $this->markTestIncomplete();

        $obj = $this->getRuleresolver();

        $ruleset =  "TestConstraint1,10";
        $generated = $this->callPrivateMethod($obj, 'buildRule', ['prop1', $ruleset]);
        $this->assertEquals(true, $generated instanceof TestConstraint1);
        $this->assertEquals(true, $generated->isValid(13));
        $this->assertEquals(false, $generated->isValid(9));
        $this->assertEquals([10], $generated->getParameters());
        $this->assertEquals('TestConstraint1', $generated->name());
    }

    /**
    *   @test
    */
    public function faildbuildRule()
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('not defined constraint:validation.DUMMY');
        $obj = $this->getRuleresolver();

        $ruleset =  "DUMMY,10";
        $generated = $this->callPrivateMethod($obj, 'buildRule', ['prop1', $ruleset]);
    }

    /**
    *   @test
    */
    public function parseStringRule()
    {
//      $this->markTestIncomplete();

        $obj = $this->getRuleresolver();

        $ruleset =  "TestConstraint1,10|TestConstraint2,5,20";
        $generated = $this->callPrivateMethod($obj, 'parseStringRule', ['prop1', $ruleset]);

        $this->assertEquals(true, $generated[0] instanceof TestConstraint1);
        $this->assertEquals(true, ($generated[0])->isValid(13));
        $this->assertEquals([10], ($generated[0])->getParameters());
        $this->assertEquals(true, $generated[1] instanceof TestConstraint2);
        $this->assertEquals(true, ($generated[1])->isValid(13));
        $this->assertEquals([5, 20], ($generated[1])->getParameters());
    }

    /**
    *   @test
    */
    public function resolve()
    {
//      $this->markTestIncomplete();

        $obj = $this->getRuleresolver();

        $ruleset =  "TestConstraint1,10|TestConstraint2,5,20";
        $values = [
            'prop1' => 13,
            'prop2' => 15,
        ];

        $generated = $obj->resolve('prop1', $values, $ruleset);

        $this->assertEquals(true, ($generated[0]) instanceof Validation);

        $this->assertEquals('prop1', ($generated[0])->attribute());
        $this->assertEquals(13, ($generated[0])->value());
        $this->assertEquals([10], ($generated[0])->parameters());
        $this->assertEquals('TestConstraint1', ($generated[0])->constraint());
        $this->assertEquals(true, ($generated[0])->isValid());
        $this->assertEquals('', ($generated[0])->message());

        $this->assertEquals('prop1', ($generated[1])->attribute());
        $this->assertEquals(13, ($generated[1])->value());
        $this->assertEquals([5, 20], ($generated[1])->parameters());
        $this->assertEquals('OverWriteNameMethod', ($generated[1])->constraint());
        $this->assertEquals(true, ($generated[1])->isValid());
        $this->assertEquals('OverWriteNameMethod value=13 param=20', ($generated[1])->message());
    }

    /**
    *   @test
    */
    public function notHasParameterRule()
    {
//      $this->markTestIncomplete();

        $obj = $this->getRuleresolver();

        $ruleset =  "TestConstraint3";
        $values = [
            'prop1' => 13,
            'prop2' => 15,
        ];

        $generated = $obj->resolve('prop1', $values, $ruleset);

        $this->assertEquals('prop1', ($generated[0])->attribute());
        $this->assertEquals(13, ($generated[0])->value());
        $this->assertEquals([], ($generated[0])->parameters());
        $this->assertEquals('TestConstraint3', ($generated[0])->constraint());
        $this->assertEquals(true, ($generated[0])->isValid());
    }
}
