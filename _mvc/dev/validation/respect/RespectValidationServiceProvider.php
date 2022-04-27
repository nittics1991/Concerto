<?php

/**
*   RespectValidationServiceProvider
*
*   @version 191216
*/

declare(strict_types=1);

namespace dev\validation\respect;

use Respect\Validation\RespectConstraintServiceProvider;
use dev\container\provider\AbstractServiceProvider;
use dev\container\ServiceContainer;
use dev\container\ServiceProviderContainer;
use dev\template\CurlyBracketMessageGenerator;
use dev\validation\ConstraintServiceProvider;
use dev\validation\MessageGenerator;
use dev\validation\RuleResolver;
use dev\validation\Validation;
use dev\validation\respect\RespectConverterServiceProvider;
use dev\validation\respect\RespectRuleResolver;

class RespectValidationServiceProvider extends AbstractServiceProvider
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
            //user ruleを先に
            $concrete->addServiceProvider(
                RespectConverterServiceProvider::class
            );
            $concrete->addServiceProvider(
                RespectUserConstraintServiceProvider::class
            );
            $concrete->addServiceProvider(
                RespectConstraintServiceProvider::class
            );
            return $concrete;
        });

        $this->bind('validation.RuleResolver', function ($container) {
            return new RespectRuleResolver(
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
