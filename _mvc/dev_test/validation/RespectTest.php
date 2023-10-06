<?php

namespace test\Concerto\validation;

use Psr\Container\ContainerInterface;
use dev\container\ServiceContainer;
use dev\container\ServiceProviderContainer;
use test\Concerto\ConcertoTestCase;
use dev\validation\Validation;
use dev\validation\Validator;
use dev\validation\respect\RespectValidationServiceProvider;
use test\Concerto\validation\respect\RespectOtherServiceProvider;

class RespectTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function first()
    {
        $this->markTestIncomplete();

        $container = new ServiceContainer();
        $container->delegate(new ServiceProviderContainer());
        $container->addServiceProvider(RespectValidationServiceProvider::class);

        $inputs = [
            'prop1' => 8,
            'prop2' => 18,
            'prop3' => 10,
        ];

        $ruleset = [
            'prop1' => 'LessThen,10',
            'prop2' => 'GreaterThen,10',
            'prop3' => 'Equals,10',     //not convert
        ];

        $validator = new Validator(
            $container->get('validation.RuleResolver'),
            $inputs,
            $ruleset
        );

        $this->assertEquals(true, $validator->isValid());
        $this->assertEquals([], $validator->errors());
    }

    /**
    *   @test
    */
    public function multi()
    {
        $this->markTestIncomplete();

        $container = new ServiceContainer();
        $container->delegate(new ServiceProviderContainer());
        $container->addServiceProvider(RespectValidationServiceProvider::class);

        $inputs = [
            'prop1' => 8,
            'prop2' => 18,
        ];

        $ruleset = [
            'prop1' => 'LessThen,10|GreaterThen,3',
            'prop2' => 'LessThen,20|GreaterThen,13',
        ];

        $validator = new Validator(
            $container->get('validation.RuleResolver'),
            $inputs,
            $ruleset
        );
        $this->assertEquals(true, $validator->isValid());
    }

    /**
    *   @test
    */
    public function fails()
    {
        $this->markTestIncomplete();

        $container = new ServiceContainer();
        $container->delegate(new ServiceProviderContainer());
        $container->addServiceProvider(RespectValidationServiceProvider::class);

        $inputs = [
            'prop1' => 11,
        ];

        $ruleset = [
            'prop1' => 'LessThen,10|GreaterThen,3',
        ];

        $validator = new Validator(
            $container->get('validation.RuleResolver'),
            $inputs,
            $ruleset
        );

        $this->assertEquals(false, $validator->isValid());
        $errors = $validator->errors();
        $this->assertEquals(true, ($errors[0]) instanceof Validation);

        $actual = '11 must be less than or equal to "10"';
        $expect = ($errors[0])->message();
        $this->assertEquals($actual, $expect);

        //2nd parameter error
        $inputs = [
            'prop1' => 2,
        ];

        $validator = new Validator(
            $container->get('validation.RuleResolver'),
            $inputs,
            $ruleset
        );

        $this->assertEquals(false, $validator->isValid());
        $errors = $validator->errors();
        $actual = '2 must be greater than or equal to "3"';
        $expect = ($errors[0])->message();
        $this->assertEquals($actual, $expect);
    }

    /**
    *   @test
    */
    public function multiParametersSuccess()
    {
        $this->markTestIncomplete();

        $container = new ServiceContainer();
        $container->delegate(new ServiceProviderContainer());
        $container->addServiceProvider(RespectValidationServiceProvider::class);

        $inputs = [
            'prop1' => 11,
        ];

        $ruleset = [
            'prop1' => 'Between,10,12',
        ];

        $validator = new Validator(
            $container->get('validation.RuleResolver'),
            $inputs,
            $ruleset
        );

        $this->assertEquals(true, $validator->isValid());
    }

    /**
    *   @test
    */
    public function multiParametersFailure()
    {
        $this->markTestIncomplete();

        $container = new ServiceContainer();
        $container->delegate(new ServiceProviderContainer());
        $container->addServiceProvider(RespectValidationServiceProvider::class);

        $inputs = [
            'prop1' => 9,
        ];

        $ruleset = [
            'prop1' => 'Between,10,12',
        ];

        $validator = new Validator(
            $container->get('validation.RuleResolver'),
            $inputs,
            $ruleset
        );

        $this->assertEquals(false, $validator->isValid());

        $errors = $validator->errors();
        $expect = ($errors[0])->constraint();
        $this->assertEquals('Between', $expect);

        $actual = '- 9 must be greater than or equal to "10"';
        $expect = ($errors[0])->message();
        $this->assertEquals($actual, $expect);
    }

    /**
    *   @test
    */
    public function setMessage()
    {
        $this->markTestIncomplete();

        $container = new ServiceContainer();
        $container->delegate(new ServiceProviderContainer());
        $container->addServiceProvider(RespectValidationServiceProvider::class);

        $inputs = [
            'prop1' => 9,
        ];

        $ruleset = [
            'prop1' => 'Between,10,12',
        ];

        $messages = [
            'Between' => 'prop={{attribute}} params={{parameters0}},{{parameters1}} value={{value}}',
        ];

        $validator = new Validator(
            $container->get('validation.RuleResolver'),
            $inputs,
            $ruleset,
            $messages
        );

        $this->assertEquals(false, $validator->isValid());

        $errors = $validator->errors();
        $expect = ($errors[0])->constraint();
        $this->assertEquals('Between', $expect);

        $actual = 'prop=prop1 params=10,12 value=9';
        $expect = ($errors[0])->message();
        $this->assertEquals($actual, $expect);
    }

    /**
    *   @test
    */
    public function userRule()
    {
        $this->markTestIncomplete();

        $container = new ServiceContainer();
        $container->delegate(new ServiceProviderContainer());
        $container->addServiceProvider(RespectValidationServiceProvider::class);

        $inputs = [
            'prop1' => 'ｶﾀｶﾅ',
        ];

        $ruleset = [
            'prop1' => 'HanKatakana',
        ];

        $validator = new Validator(
            $container->get('validation.RuleResolver'),
            $inputs,
            $ruleset
        );
        $this->assertEquals(true, $validator->isValid());

        //fails
        $inputs = [
            'prop1' => 'ｶﾀｶﾅ半角',
        ];

        $validator = new Validator(
            $container->get('validation.RuleResolver'),
            $inputs,
            $ruleset
        );
        $this->assertEquals(false, $validator->isValid());
        $errors = $validator->errors();
        $expect = ($errors[0])->constraint();
        $this->assertEquals('HanKatakana', $expect);

        $msg = '"ｶﾀｶﾅ半角" must contain only katakana';
        $this->assertEquals($msg, ($errors[0])->message());
    }
}
