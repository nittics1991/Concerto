<?php

namespace Concerto\test\validation;

use Psr\Container\ContainerInterface;
use Concerto\container\ServiceContainer;
use Concerto\container\ServiceProviderContainer;
use Concerto\test\ConcertoTestCase;
use Concerto\validation\ValidationServiceProvider;
use Concerto\validation\Validator;

/**
*   @group excludes
*/
class ValidationServiceProviderTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function first()
    {
//      $this->markTestIncomplete();

        $container = new ServiceContainer();
        $container->delegate(new ServiceProviderContainer());
        $container->addServiceProvider(ValidationServiceProvider::class);

        $this->assertEquals(true, $container->has('validation.Container'));

        $obj = $container->get('validation.Container');
        $this->assertEquals(true, $obj instanceof ContainerInterface);

        $this->assertEquals(true, $obj->has('validation.GreaterThen'));

        $constraint = $obj->get('validation.GreaterThen');
        $constraint->setParameters([10]);
        $this->assertEquals(true, $constraint->isValid(12));
    }

    /**
    *   @test
    */
    public function allPattern()
    {
//      $this->markTestIncomplete();

        $container = new ServiceContainer();
        $container->delegate(new ServiceProviderContainer());
        $container->addServiceProvider(ValidationServiceProvider::class);

        $inputs = [
            'prop1' => 10,
            'prop2' => 20,
            'prop3' => 30,
        ];

        $ruleset = [
            'prop1' => 'Equals,10|LessThen,11',
            'prop2' => 'Equals,20|LessThen,21',
            'prop3' => 'IsInt',
        ];

        $validator = new Validator(
            $container->get('validation.RuleResolver'),
            $inputs,
            $ruleset
        );

        $this->assertEquals(true, $validator->isValid());
    }
}
