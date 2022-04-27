<?php

/**
*   AbstractConstraint
*
*   @ver 180712
*/

declare(strict_types=1);

namespace dev\validation;

use dev\validation\ConstraintInterface;

abstract class AbstractConstraint implements ConstraintInterface
{
    /**
    *   params
    *
    *   @return array
    */
    protected $parameters = [];

    /**
    *   value
    *
    *   @return mixed
    */
    protected $value;

    /**
    *   message
    *
    *   @return string
    */
    protected $message = '';

    /**
    *   attribute
    *
    *   @return string
    */
    protected $attribute = '';

    /**
    *   __construct
    *
    *   @param array
    */
    public function __construct(array $parameters = [])
    {
        $this->setParameters($parameters);
    }

    /**
    *   {inherit}
    *
    */
    abstract public function isValid($val);

    /**
    *   {inherit}
    *
    */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
    *   {inherit}
    *
    *   @return $this
    */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
    *   {inherit}
    *
    */
    public function name()
    {
        $splited = explode('\\', get_class($this));
        return array_pop($splited);
    }

    /**
    *   {inherit}
    *
    */
    public function message()
    {
        return mb_ereg_replace(':attribute', $this->attribute, $this->message);
    }

    /**
    *   {inherit}
    *
   *   @return $this
    */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
        return $this;
    }
}
