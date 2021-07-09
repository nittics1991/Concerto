<?php

/**
*   RuleConverterInterface
*
*   @ver 180620
*/

declare(strict_types=1);

namespace Concerto\validation;

interface RuleConverterInterface
{
    /**
    *   convert
    *
    *   @return string
    */
    public function convert();
}
