<?php

/**
*   RespectRuleResolver
*
*   @ver 180620
*/

declare(strict_types=1);

namespace dev\validation\respect;

use dev\validation\AbstractConstraint;
use dev\validation\RuleResolver;

class RespectRuleResolver extends RuleResolver
{
    /**
    *   converterId
    *
    *   @var string
    */
    private $converterId = 'converter';

    /**
    *   buildRule
    *
    *   @param string
    *   @param string
    *   @return ConstraintInterface
    */
    protected function buildRule($attribute, $ruleset)
    {
        $ruleAndparameters = $this->convertRule($ruleset);
        return $this->wrapConstraint(
            $ruleAndparameters[0],
            $ruleAndparameters[1]
        );
    }

    /**
    *   convertRule
    *
    *   @param array
    *   @return array [constraint, [args]]
    */
    protected function convertRule($ruleset)
    {
        $params = explode(',', $ruleset);
        $ruleName = ucfirst(array_shift($params));

        //converter
        $id = !empty($this->converterId) ?
            $this->converterId . '.' . $ruleName : $ruleName;

        if ($this->container->has($id)) {
            $converter = $this->container->get($id);
            $ruleName = $converter->convert();
        }

        //container
        $id = !empty($this->prefixId) ?
            $this->prefixId . '.' . $ruleName : $ruleName;

        if ($this->container->has($id)) {
            $this->container->raw(
                "{$this->prefixId}.constructParameters",
                $params
            );
            return [$this->container->get($id), $params];
        }
        throw new \InvalidArgumentException("not define rule:{$ruleName}");
    }

    /**
    *   wrapConstraint
    *
    *   @param \\Respect\Validation\Rule
    *   @param array
    *   @return Validation
    */
    protected function wrapConstraint($constraint, array $params)
    {
        return
            new class ($constraint, $params) extends AbstractConstraint
            {
                protected $message = 'Callable Constraint:value=%s';
                protected $constraint;
                protected $params;

                public function __construct($constraint, array $params)
                {
                    $this->constraint = $constraint;
                    $this->parameters = $params;
                }

                public function isValid($val)
                {
                    $this->value = $val;

                    try {
                        return (bool)call_user_func(
                            [$this->constraint, 'assert'],
                            $val
                        );
                    } catch (\Exception $e) {
                            $this->message =
                                method_exists($e, 'getFullMessage') ?
                            $e->getFullMessage() :
                            $e->getMessage();
                    }
                    return false;
                }

                public function name()
                {
                    $namespace = get_class($this->constraint);
                    $splited = explode('\\', $namespace);
                    return $splited[count($splited) - 1];
                }
            };
    }
}
