<?php

/**
*   login counter interface
*
*   @version 160323
*
**/

namespace Concerto\auth;

interface AuthCounterInterface
{
    public function isValid();
    public function log($param);
}
