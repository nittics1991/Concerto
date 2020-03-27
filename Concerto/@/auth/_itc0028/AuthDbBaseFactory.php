<?php

/**
*   factory
*
*   @version 160715
*/

namespace Concerto\auth;

use Concerto\auth\AuthDbBaseFactoryInterface;
use Concerto\database\LoginInf;
use Concerto\database\LoginInfData;
use Concerto\database\MstTanto;
use Concerto\database\MstTantoData;
use Concerto\standard\Session;

class AuthDbBaseFactory implements AuthDbBaseFactoryInterface
{
    /**
    *   データベース
    *
    *   @var resorce
    */
    private $pdo;
    
    /**
    *   コンストラクタ
    *
    *   @param resorce データベース
    */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    
    /**
    *   factory
    */
    public function getLoginInf()
    {
        return new LoginInf($this->pdo);
    }
    
    public function getLoginInfData()
    {
        return new LoginInfData();
    }
    
    public function getMstTanto()
    {
        return new MstTanto($this->pdo);
    }
    
    public function getMstTantoData()
    {
        return new MstTantoData();
    }
    
    public function getSession()
    {
        return new Session('auth');
    }
}
