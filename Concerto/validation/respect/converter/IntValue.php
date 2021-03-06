<?php

/**
*   RespectRuleConverter
*
*   @ver 180620
*/

declare(strict_types=1);

namespace Concerto\validation\respect\converter;

use Concerto\validation\RuleConverterInterface;

class IntValue implements RuleConverterInterface
{
    /**
    *   {inherit}
    *
    */
    public function convert()
    {
        return 'IntVal';
    }
}
