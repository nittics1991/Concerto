<?php

/**
*   ValidationInterface
*
*   @ver 180618
*/

declare(strict_types=1);

namespace dev\validation;

interface ValidationInterface
{
    /**
    *   create
    *
    *   @param string
    *   @param mexed
    *   @param ConstraintInterface
    *   @param string
    *   @return $this
    */
    public function create(
        $attribute,
        $value,
        ConstraintInterface $constraint,
        $message
    );

    /**
    *   isValid
    *
    *   @return bool
    */
    public function isValid();

    /**
    *   attribute
    *
    *   @return string
    */
    public function attribute();

    /**
    *   constraint
    *
    *   @return string
    */
    public function constraint();

    /**
    *   value
    *
    *   @return mixed
    */
    public function value();

    /**
    *   parameters
    *
    *   @return array
    */
    public function parameters();

    /**
    *   message
    *
    *   @return string
    */
    public function message();
}
