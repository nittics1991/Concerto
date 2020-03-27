<?php

/**
*   AbacRuleContainerInterface
*
*   @version 180515
**/

namespace Concerto\auth\abac;

interface AbacRuleContainerInterface
{
    /**
    *   get
    *
    *   @param string
    **/
    public function get($id);
}
