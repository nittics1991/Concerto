<?php

/**
*   RespectRuleConverter
*
*   @ver 180620
*/

declare(strict_types=1);

namespace dev\validation\respect\converter;

use dev\validation\RuleConverterInterface;

class FloatValue implements RuleConverterInterface
{
    /**
    *   {inherit}
    *
    */
    public function convert()
    {
        return 'FloatVal';
    }
}
