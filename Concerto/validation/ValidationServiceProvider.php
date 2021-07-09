<?php

/**
*   ValidationServiceProvider
*
*   @version 180618
*/

declare(strict_types=1);

namespace Concerto\validation;

use Concerto\container\provider\AbstractServiceProvider;
use Concerto\container\ServiceContainer;
use Concerto\container\ServiceProviderContainer;
use Concerto\template\CurlyBracketMessageGenerator;
use Concerto\validation\ConstraintServiceProvider;
use Concerto\validation\MessageGenerator;
use Concerto\validation\RuleResolver;
use Concerto\validation\Validation;

class ValidationServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'validation.Container',
        'validation.RuleResolver',
        'validation.Validation',
        'validation.MessageGenerator',
        RuleResolverInterface::class,
    ];

    public function register()
    {
        $this->bind('validation.Container', function ($container) {
            $concrete = new ServiceContainer();
            $concrete->delegate(new ServiceProviderContainer());
            $concrete->addServiceProvider(ConstraintServiceProvider::class);
            return $concrete;
        });

        $this->bind('validation.RuleResolver', function ($container) {
            return new RuleResolver(
                $container->get('validation.Container'),
                $container->get('validation.Validation')
            );
        });

        $this->bind('validation.Validation', function ($container) {
            return new Validation(
                $container->get('validation.MessageGenerator')
            );
        });

        $this->bind('validation.MessageGenerator', function ($container) {
            return new MessageGenerator(
                new CurlyBracketMessageGenerator()
            );
        });

        $this->bind(RuleResolverInterface::class, function ($container) {
            return $container->get('validation.RuleResolver');
        });
    }
}
