<?php

//namespace dev\Valodator;

//use \BadMethodCallException;
//use InvalidArgumentException;
//use dev\Valodator\OperationLawTypeEnum;

class RuleOperator
{
    /**
    *    operation
    *
    *    @var string
    */
    private $operation = '';

    /**
    *    priority
    *
    *    @var int
    */
    private $priority = 0;

    /**
    *    law
    *
    *    @var RuleOperatorLaw
    */
    private $law;

    /**
    *    action
    *
    *    @var string
    */
    private $action;

    /**
    *    __construct
    *
    *    @param array $data
    */
    public function __construct(array $data)
    {
        foreach ($data as $key => $val) {
            if (property_exists(static::class, $key)) {
                $this->$key = $val;
            }
        }
        $this->validate();
    }

    /**
    *    validate
    *
    */
    private function validate()
    {
        $propeties = ['operation', 'priority', 'law', 'action'];
        foreach ($propeties as $property) {
            $validator = 'valid' . ucfirst($property);
            $getter = 'get' . ucfirst($property);

            if (!$this->$validator($this->$getter())) {
                throw new InvalidArgumentException(
                    "invalid:{$property}"
                );
            }
        }
    }

    private function validOperation($val): bool
    {
        return is_string($val);
    }

    private function validPriority($val): bool
    {
        return is_int($val);
    }

    private function validLaw($val): bool
    {
        return ($val instanceof OperationLawTypeEnum);
    }

    private function validAction($val): bool
    {
        return is_string($val);
    }

    /**
    *    getter method
    *    @inheritDoc
    *
    */
    public function __call(string $name, array $arguments)
    {
        if (mb_ereg_match('\Aget', $name)) {
            $propertyName = mb_strtolower(mb_substr($name, 3));
            if (property_exists(static::class, $propertyName)) {
                return $this->$propertyName;
            }
        }
        throw new BadMethodCallException(
            "method not defined:{$name}"
        );
    }
}
