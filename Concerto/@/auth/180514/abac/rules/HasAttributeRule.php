<?php

/**
*   HasAttributeRule
*
*   @version 180515
**/

namespace Concerto\auth\abac\rules;

class HasAttributeRule implements AbacRuleInterface
{
    /**
    *   {inherit}
    *
    **/
    public function isValid(AbacUserInterface $user, array $params)
    {
        $attr = current($params);
        return $user->hasAttribute($attr);
    }
}
