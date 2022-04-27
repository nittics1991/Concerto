<?php

/**
*   Validation
*
*   @ver 180619
*/

declare(strict_types=1);

namespace dev\validation;

use dev\validation\ConstraintInterface;
use dev\validation\MessageGeneratorInterface;
use dev\validation\ValidationInterface;

class Validation implements ValidationInterface
{
    /**
    *   messageGenerator
    *
    *   @var MessageGeneratorInterface
    */
    protected $messageGenerator;

    /**
    *   attribute
    *
    *   @var string
    */
    protected $attribute;

    /**
    *   values
    *
    *   @var array
    */
    protected $value;

    /**
    *   constraint
    *
    *   @var ConstraintInterface
    */
    protected $constraint;

    /**
    *   constraint
    *
    *   @var bool
    */
    protected $hasMessage = false;

    /**
    *   __construct
    *
    *   @param MessageGeneratorInterface
    */
    public function __construct(
        MessageGeneratorInterface $messageGenerator
    ) {
        $this->messageGenerator = $messageGenerator;
    }

    /**
    *   create
    *
    *   @param string
    *   @param mexed
    *   @param ConstraintInterface
    *   @param string
    *   @return Validation
    */
    public function create(
        $attribute,
        $value,
        ConstraintInterface $constraint,
        $message = null
    ) {
        $object = new static(
            $this->setMessage($message)
        );
        $object->attribute = $attribute;
        $object->value = $value;
        $object->constraint = $constraint;
        $object->hasMessage = isset($message);
        return $object;
    }

    /**
    *   setMessage
    *
    *   @param string
    *   @return MessageGeneratorInterface
    */
    protected function setMessage($message)
    {
        if (isset($message)) {
            $this->hasMessage = true;
            return $this->messageGenerator->create($message);
        }
        return $this->messageGenerator;
    }

    /**
    *   isValid
    *
    *   @return bool
    */
    public function isValid()
    {
        return $this->constraint->isValid($this->value);
    }

    /**
    *   {inherit}
    *
    */
    public function attribute()
    {
        return $this->attribute;
    }

    /**
    *   {inherit}
    *
    */
    public function constraint()
    {
        return $this->constraint->name();
    }

    /**
    *   {inherit}
    *
    */
    public function value()
    {
        return $this->value;
    }

    /**
    *   {inherit}
    *
    */
    public function parameters()
    {
        return $this->constraint->getParameters();
    }

    /**
    *   {inherit}
    *
    */
    public function message()
    {
        if (!$this->hasMessage) {
            return $this->constraint->message();
        }
        return $this->messageGenerator->generate($this);
    }
}
