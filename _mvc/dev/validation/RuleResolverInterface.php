<?php

/**
*   RuleResolverInterface
*
*   @ver 180614
*/

declare(strict_types=1);

namespace dev\validation;

interface RuleResolverInterface
{
    /**
    *   resolve
    *
    *   @param string
    *   @param array
    *   @param string|Closure|ConstraintInterface|callable
    *   @return array [ValidationInterface, ...]
    */
    public function resolve($name, array $values, $rule);
}
