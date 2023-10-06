<?php

declare(strict_types=1);

namespace test\Concerto\validation;

use test\Concerto\ConcertoTestCase;
use dev\container\ServiceContainer;
use dev\template\CurlyBracketMessageGenerator;
use dev\validation\MessageGenerator;
use dev\validation\Validator;
use dev\validation\Validation;
use dev\validation\RuleResolver;
use test\Concerto\validation\constraint\TestConstraint1;
use test\Concerto\validation\constraint\TestConstraint2;
use dev\validation\ValidationInterface;

class ValidatorTest extends ConcertoTestCase
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

        $obj = new RuleResolver(
            $container,
            new Validation(new MessageGenerator(
                new CurlyBracketMessageGenerator()
            ))
        );

        return $obj;
    }

    public function firstProvider()
    {
        return [
            [
                ['prop1' => 10, 'prop2' => 20, 'prop3' => 30],
                [
                    'prop1' => new TestConstraint1([5]),
                    'prop2' => function ($val, $inputs) {
                        if ($inputs['prop1'] > 10) {
                            return $val < 10;
                        }
                        return $val > 10;
                    },
                    'prop3' => 'TestConstraint1,20|TestConstraint2,20,40',
                ],
                $this->getRuleresolver(),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider firstProvider
    */
    public function first($values, $rules, $resolver)
    {
//      $this->markTestIncomplete();
        $obj = new Validator($resolver, $values, $rules);
        $this->assertEquals(true, $obj->isValid());
        $this->assertEquals(false, $obj->fails());
        $this->assertEquals([], $obj->errors());
    }

    public function failureProvider()
    {
        return [
            [
                ['prop1' => 10, 'prop2' => 20, 'prop3' => 30],
                [
                    'prop1' => new TestConstraint1([20]),
                    'prop2' => function ($val, $inputs) {
                        if ($inputs['prop1'] > 10) {
                            return $val < 10;
                        }
                        return $val > 20;
                    },
                    'prop3' => 'TestConstraint1,40|TestConstraint2,20,30',
                ],
                $this->getRuleresolver(),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider failureProvider
    */
    public function failure($values, $rules, $resolver)
    {
//      $this->markTestIncomplete();
        $obj = new Validator($resolver, $values, $rules);
        $this->assertEquals(false, $obj->isValid());
        $this->assertEquals(true, $obj->fails());

        $errors = $obj->errors();
        $this->assertEquals(4, count($errors));

        $this->assertEquals(true, $errors[0] instanceof ValidationInterface);
        $this->assertEquals('prop1', ($errors[0])->attribute());
        $this->assertEquals(10, ($errors[0])->value());
        $this->assertEquals([20], ($errors[0])->parameters());
        $this->assertEquals('TestConstraint1', ($errors[0])->constraint());
        $this->assertEquals('', ($errors[0])->message());

        $this->assertEquals(true, $errors[1] instanceof ValidationInterface);
        $this->assertEquals('prop2', ($errors[1])->attribute());
        $this->assertEquals(20, ($errors[1])->value());
        $this->assertEquals([], ($errors[1])->parameters());
        $this->assertEquals('class@anonymous', ($errors[1])->constraint());
        $this->assertEquals('prop2:Closure Constraint:value=20', ($errors[1])->message());

        $this->assertEquals(true, $errors[2] instanceof ValidationInterface);
        $this->assertEquals('prop3', ($errors[2])->attribute());
        $this->assertEquals(30, ($errors[2])->value());
        $this->assertEquals([40], ($errors[2])->parameters());
        $this->assertEquals('TestConstraint1', ($errors[2])->constraint());
        $this->assertEquals('', ($errors[2])->message());

        $this->assertEquals(true, $errors[3] instanceof ValidationInterface);
        $this->assertEquals('prop3', ($errors[3])->attribute());
        $this->assertEquals(30, ($errors[3])->value());
        $this->assertEquals([20, 30], ($errors[3])->parameters());
        $this->assertEquals('OverWriteNameMethod', ($errors[3])->constraint());
        $this->assertEquals('OverWriteNameMethod value=30 param=30', ($errors[3])->message());
    }

    /**
    *   @test
    *   @dataProvider failureProvider
    */
    public function immediately($values, $rules, $resolver)
    {
//      $this->markTestIncomplete();
        $obj = new Validator($resolver, $values, $rules);
        $obj->immediately();
        $this->assertEquals(false, $obj->isValid());
        $this->assertEquals(true, $obj->fails());

        $errors = $obj->errors();
        $this->assertEquals(1, count($errors));

        $this->assertEquals(true, $errors[0] instanceof ValidationInterface);
        $this->assertEquals('prop1', ($errors[0])->attribute());
        $this->assertEquals(10, ($errors[0])->value());
        $this->assertEquals([20], ($errors[0])->parameters());
        $this->assertEquals('TestConstraint1', ($errors[0])->constraint());
    }

    /**
    *   @test
    */
    public function setMessage()
    {
//      $this->markTestIncomplete();
        $obj = new Validator(
            $this->getRuleresolver(),
            [
                'prop1' => 10
            ],
            [
                    'prop1' => new TestConstraint1([20]),
            ],
            [
                'TestConstraint1' => 'set message pattern param={{parameters0}} value={{value}}'
            ]
        );
        $this->assertEquals(false, $obj->isValid());
        $errors = $obj->errors();
        $this->assertEquals('set message pattern param=20 value=10', ($errors[0])->message());
    }

    /**
    *   @test
    *   @dataProvider failureProvider
    */
    public function failureMessages($values, $rules, $resolver)
    {
//      $this->markTestIncomplete();
        $obj = new Validator($resolver, $values, $rules);
        $this->assertEquals(false, $obj->isValid());
        $this->assertEquals(true, $obj->fails());

        $errors = $obj->errors();
        $this->assertEquals(4, count($errors));

        $expects = [
            '',
            'prop2:Closure Constraint:value=20',
            '',
            'OverWriteNameMethod value=30 param=30'
        ];

        $this->assertEquals($expects, $obj->messages());

        //setMessage
        $messages = [
            'TestConstraint1' => 'set message pattern param={{parameters0}} value={{value}}'
        ];

        $obj = new Validator($resolver, $values, $rules, $messages);
        $this->assertEquals(false, $obj->isValid());
        $this->assertEquals(true, $obj->fails());

        $errors = $obj->errors();
        $this->assertEquals(4, count($errors));

        $expects = [
            'set message pattern param=20 value=10',
            'prop2:Closure Constraint:value=20',
            'set message pattern param=40 value=30',
            'OverWriteNameMethod value=30 param=30'
        ];

        $this->assertEquals($expects, $obj->messages());
    }
}
