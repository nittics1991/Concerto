<?php

/**
*   factoryインターフェース
*
*   @version 160715
*/

namespace Concerto\auth;

interface AuthDbBaseFactoryInterface
{
    /**
    *   DI
    */
    public function getLoginInf();
    public function getLoginInfData();
    public function getMstTanto();
    public function getMstTantoData();
    public function getSession();
}
