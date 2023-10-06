<?php

/**
*   CurlBracketMessage
*
*   @ver 180618
*/

declare(strict_types=1);

namespace dev\validation;

use dev\validation\ValidationInterface;

interface MessageGeneratorInterface
{
    /**
    *   create
    *
    *   @param string
    *   @return MessageGeneratorInterface
    */
    public function create($message);

    /**
    *   generate
    *
    *   @param ValidationInterface
    *   @return string
    */
    public function generate(ValidationInterface $validation);
}
