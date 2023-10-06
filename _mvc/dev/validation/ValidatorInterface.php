<?php

/**
*   ValidatorInterface
*
*   @ver 180612
*/

declare(strict_types=1);

namespace dev\validation;

interface ValidatorInterface
{
    /**
    *   isValid
    *
    *   @return bool
    */
    public function isValid();

    /**
    *   errors
    *
    *   @return array
    */
    public function errors();
}
