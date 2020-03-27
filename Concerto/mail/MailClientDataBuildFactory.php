<?php

/**
*   factory
*
*   @version 151110
*/

declare(strict_types=1);

namespace Concerto\mail;

use PDO;
use Concerto\database\MailCcInf;
use Concerto\database\MailCcInfData;
use Concerto\database\MailInf;
use Concerto\standard\Session;
use Concerto\standard\ViewStandard;
use Concerto\mail\MailClientDataBuildFactoryInterface;

class MailClientDataBuildFactory implements MailClientDataBuildFactoryInterface
{
    /**
    *   pdo
    *
    *   @var PDO
    */
    private $pdo;
    
    /**
    *   __construct
    *
    *   @param PDO $pdo
    */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    /**
    *   factory
    */
    public function getPdo()
    {
        return $this->pdo;
    }
    
    public function getMailCcInf()
    {
        return new MailCcInf($this->pdo);
    }
    
    public function getMailCcInfData()
    {
        return new MailCcInfData();
    }
    
    public function getMailInf()
    {
        return new MailInf($this->pdo);
    }
    
    public function getSession($namespace = null)
    {
        return new Session($namespace);
    }
    
    public function getViewStandard()
    {
        return new ViewStandard();
    }
}
