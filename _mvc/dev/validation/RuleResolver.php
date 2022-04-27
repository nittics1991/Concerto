<?php

/**
*   RuleResolver
*
*   @ver 180619
*
*   @example $rules = [
*       'property1' => ConstraintObject,
*       'property2' => function($val, array $inputs):bool {return $val = 2;},
*       'property3' => callbable,
*       'property4' => 'ConstraintA,para1,para2|ConstraintB,para11,para12',
*       ];
*/

declare(strict_types=1);

namespace dev\validation;

use Psr\Container\ContainerInterface;
use dev\validation\AbstractConstraint;
use dev\validation\RuleResolverInterface;
use dev\validation\Validation;

class RuleResolver implements RuleResolverInterface
{
    /**
    *   container
    *
    *   @var ContainerInterface
    */
    protected $container;

    /**
    *   validation
    *
    *   @var ValidationInterface
    */
    protected $validation;

    /**
    *   prefixId
    *
    *   @var string
    */
    protected $prefixId = 'validation';

    /**
    *   __construct
    *
    */
    public function __construct(
        ContainerInterface $container,
        ValidationInterface $validation
    ) {
        $this->container = $container;
        $this->validation = $validation;
    }

    /**
    *   {inherit}
    *
    */
    public function resolve($attribute, array $values, $rule, $messages = [])
    {
        $constraints = $this->parseRule($attribute, $values, $rule);
        $value = $values[$attribute];
        $validation = $this->validation;

        return array_map(
            function ($constraint) use (
                $attribute,
                $value,
                $validation,
                $messages
            ) {
                return $validation->create(
                    $attribute,
                    $value,
                    $constraint,
                    array_key_exists($constraint->name(), $messages) ?
                        $messages[$constraint->name()] : null
                );
            },
            $constraints
        );
    }

    /**
    *   parseRule
    *
    *   @param string
    *   @param array
    *   @param string|Closure|ConstraintInterface|callable
    *   @return array [ConstraintInterface, ...]
    *   @throws InvalidArgumentException
    */
    protected function parseRule($attribute, array $values, $rule)
    {
        if ($rule instanceof ConstraintInterface) {
            return $this->parseConstraintRule($attribute, $rule);
        }
        if ($rule instanceof \Closure) {
            return $this->parseClosureRule($attribute, $rule, $values);
        }
        if (is_callable($rule)) {
            return $this->parseCallableRule($attribute, $rule, $values);
        }
        if (is_string($rule)) {
            return $this->parseStringRule($attribute, $rule);
        }

        throw new \InvalidArgumentException(
            "rule must be string|Closure|ConstraintInterface:{$attribute}"
        );
    }

    /**
    *   parseObjectRule
    *
    *   @param string
    *   @param ConstraintInterface
    *   @return array [ConstraintInterface, ...]
    */
    protected function parseConstraintRule($attribute, $obj)
    {
        $obj = $obj->setAttribute($attribute);
        return [$obj];
    }

    /**
    *   parseClosureRule
    *
    *   @param string
    *   @param Closure
    *   @param array
    *   @return array [ConstraintInterface, ...]
    */
    protected function parseClosureRule(
        $attribute,
        \Closure $closure,
        array $values
    ) {
        return [
            new class ($attribute, $closure, $values) extends AbstractConstraint
            {
                protected $message = ':attribute:Closure Constraint:value=%s';
                protected $attribute;
                protected $closure;
                protected $values;


                public function __construct($attribute, $closure, $values)
                {
                    $this->attribute = $attribute;
                    $this->closure = $closure;
                    $this->values = $values;
                }

                public function isValid($val)
                {
                    $this->value = $val;

                    return (bool)call_user_func(
                        $this->closure,
                        $val,
                        $this->values
                    );
                }

                public function name()
                {
                    return 'class@anonymous';
                }

                public function message()
                {
                    $message = mb_ereg_replace(
                        ':attribute',
                        $this->attribute,
                        $this->message
                    );
                    return sprintf($message, $this->value);
                }
            }
        ];
    }

    /**
    *   parseCallableRule
    *
    *   @param string
    *   @param callable
    *   @param array
    *   @return array [ConstraintInterface, ...]
    */
    protected function parseCallableRule(
        $attribute,
        callable $callback,
        array $values
    ) {
        return [
            new class ($attribute, $callback, $values) extends AbstractConstraint
            {
                protected $message = ':attribute:Callable Constraint:value=%s';
                protected $attribute;
                protected $callback;
                protected $values;

                public function __construct($attribute, $callback, $values)
                {
                    $this->attribute = $attribute;
                    $this->callback = $callback;
                    $this->values = $values;
                }

                public function isValid($val)
                {
                    $this->value = $val;

                    return (bool)call_user_func(
                        $this->callback,
                        $val,
                        $this->values
                    );
                }

                public function name()
                {
                    return 'class@anonymous';
                }

                public function message()
                {
                    $message = mb_ereg_replace(
                        ':attribute',
                        $this->attribute,
                        $this->message
                    );
                    return sprintf($message, $this->value);
                }
            }
        ];
    }

    /**
    *   parseStringRule
    *
    *   @param string
    *   @param string
    *   @return array [ConstraintInterface, ...]
    */
    protected function parseStringRule($attribute, $rules)
    {
        $constraints = [];

        foreach (explode('|', $rules) as $rule) {
            $constraints[] = $this->buildRule($attribute, $rule);
        }
        return $constraints;
    }

    /**
    *   buildRule
    *
    *   @param string
    *   @param string
    *   @return ConstraintInterface
    *   @throws InvalidArgumentException
    */
    protected function buildRule($attribute, $ruleset)
    {
        $params = explode(',', $ruleset);
        $id = !empty($this->prefixId) ?
            $this->prefixId . '.' . array_shift($params) :
            array_shift($params);

        if (!$this->container->has($id)) {
            throw new \InvalidArgumentException(
                "not defined constraint:{$id}"
            );
        }

        return $this->container->get($id)
            ->setParameters($params)
            ->setAttribute($attribute);
    }
}
