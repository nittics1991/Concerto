<?php

/**
*   AbacRuleInterface
*
*   @version 180515
**/

namespace Concerto\auth\abac;

interface AbacRuleInterface
{
    /**
    *   isValid
    *
    *   @param AbacUserInterface
    *   @param array
    *   @return bool
    **/
    public function isValid(AbacUserInterface $user, array $params);
}
