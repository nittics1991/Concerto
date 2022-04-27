<?php

/**
*   ConstraintInterface
*
*   @ver 180712
*/

declare(strict_types=1);

namespace dev\validation;

interface ConstraintInterface
{
    /**
    *   isValid
    *
    *   @param mixed
    */
    public function isValid($val);

    /**
    *   getParams
    *
    *   @return array
    */
    public function getParameters();

    /**
    *   setParams
    *
    *   @param array
    */
    public function setParameters(array $parameters);

    /**
    *   name
    *
    *   @return array
    */
    public function name();

    /**
    *   message
    *
    *   @return string
    */
    public function message();

    /**
    *   setAttribute
    *
    *   @param string
    */
    public function setAttribute($attribute);
}
