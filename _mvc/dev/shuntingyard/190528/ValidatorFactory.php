<?php

namespace dev\Validator;

use RuntimeException;

class ValidatorFactory
{
    /**
    *     namespaces
    *
    *     @var array
    */
    protected $namespaces;

    /**
    *     __construct
    *
    *     @param array $namespaces
    */
    public function __construct(array $namespaces = [])
    {
        $this->namespaces = $namespaces;
    }

    /**
    *     addNamespace
    *
    *     @param string $namespace
    *     @return $this
    */
    public function addNamespace(string $namespace)
    {
        $this->namespaces[] = $namespace;
    }

    /**
    *     buildRule
    *
    *     @param string $ruleName
    *     @return ValidatorRuleInterface
    */
    public function buildRule(string $ruleName): ValidatorRuleInterface
    {
        foreach ($this->namespaces as $namespace) {
            try {
                return new() "{$namespace}\\{$ruleName}";
            } catch (Exception $e) {
                continue;
            }
        }
        throw new RuntimeException(
            "not defained rule:{$ruleName}",
            0,
            $e
        );
    }
}
