<?php

//use \InvalidArgumentException;
//use \IteratorAggregate;
//use dev\validator\OperationLawTypeEnum;

require_once 'OperationLawTypeEnum.php';
require_once 'RuleOperator.php';

class RuleOperators implements IteratorAggregate
{
    /**
    *    operatorDefines
    *
    *    @var array [array_operator, ...]
    */
    private $operatorDefines = [
        [
            'operation' => '+',
            'priority' => 2,
            'law' => OperationLawTypeEnum::LEFT,
            'action' => 'actionOr',
        ],
        [
            'operation' => '^',
            'priority' => 2,
            'law' => OperationLawTypeEnum::LEFT,
            'action' => 'actionXor',
        ],
        [
            'operation' => '*',
            'priority' => 3,
            'law' => OperationLawTypeEnum::LEFT,
            'action' => 'actionAnd',
        ],
        [
            'operation' => '!',
            'priority' => 4,
            'law' => OperationLawTypeEnum::RIGHT,
            'action' => 'actionNot',
        ],
        [
            'operation' => '(',
            'priority' => 5,
            'law' => OperationLawTypeEnum::LEFT,
            'action' => 'actionLbracket',
        ],
        [
            'operation' => ')',
            'priority' => 5,
            'law' => OperationLawTypeEnum::RIGHT,
            'action' => 'actionRbracket',
        ],
        [
            'operation' => ':',
            'priority' => 1,
            'law' => OperationLawTypeEnum::NON,
            'action' => 'actionLarg',
        ],
        [
            'operation' => ';',
            'priority' => 1,
            'law' => OperationLawTypeEnum::NON,
            'action' => 'actionRarg',
        ],
        [
            'operation' => ',',
            'priority' => 1,
            'law' => OperationLawTypeEnum::NON,
            'action' => 'actionSarg',
        ],
    ];

    /**
    *    operators
    *
    *    @var array [Operator, ...]
    */
    private $operators = [];

    /**
    *    operations
    *
    *    @var array
    */
    private $operations = [];

    /**
    *    priorities
    *
    *    @var array [operation => prioritiy, ...]
    */
    private $priorities = [];

    /**
    *    laws
    *
    *    @var array [operation => law, ...]
    */
    private $laws = [];

    /**
    *    actions
    *
    *    @var array [operation => action, ...]
    */
    private $actions = [];

    /**
    *    __construct
    *
    */
    public function __construct()
    {
        foreach ($this->operatorDefines as $operator) {
            $defined = [];
            foreach ($operator as $key => $val) {
                $defined[$key] = $key === 'law' ?
                    new OperationLawTypeEnum($val) : $val;
            }
            $this->operators[] = new RuleOperator($defined);
        }

        foreach ($this->operators as $operator) {
            $operation = $operator->getOperation();
            $this->operations[] = $operator->getOperation();
            $this->priorities[$operation] = $operator->getPriority();
            $this->laws[$operation] = $operator->getLaw();
            $this->actions[$operation] = $operator->getAction();
        }
    }

    /**
    *    @inheritDoc
    *
    */
    public function getIterator()
    {
        foreach ($this->operators as $operation) {
            yield $operation;
        }
    }

    /**
    *    isOperation
    *
    *     @param string $token
    *     @return bool
    */
    public function isOperation(string $token): bool
    {
        return in_array($token, $this->operations);
    }

    /**
    *    priority
    *
    *     @param string $token
    *     @return int
    */
    public function priority(string $token): int
    {
        if (!isset($this->priorities[$token])) {
            throw new InvalidArgumentException(
                "not defined priority:{$token}"
            );
        }
        return $this->priorities[$token];
    }

    /**
    *    action
    *
    *     @param string $token
    *     @return string
    */
    public function action(string $token): string
    {
        if (!isset($this->actions[$token])) {
            throw new InvalidArgumentException(
                "not defined action:{$token}"
            );
        }
        return $this->actions[$token];
    }

    /**
    *    law
    *
    *     @param string $token
    *     @return RuleOperatorLaw
    */
    public function law(string $token): OperationLawTypeEnum
    {
        if (!isset($this->laws[$token])) {
            throw new InvalidArgumentException(
                "not defined operation:{$token}"
            );
        }
        return $this->laws[$token];
    }
}
