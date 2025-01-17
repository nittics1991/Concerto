<?php

/**
*   RespectRuleConverter
*
*   @ver 180620
*/

declare(strict_types=1);

namespace dev\validation\respect\converter;

use dev\validation\RuleConverterInterface;

class GreaterThen implements RuleConverterInterface
{
    /**
    *   @inheritDoc
    *
    */
    public function convert()
    {
        return 'Min';
    }
}
