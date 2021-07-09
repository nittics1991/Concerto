<?php

/**
*   factory
*
*   @version 210616
*/

declare(strict_types=1);

namespace Concerto\mail;

use PDO;
use Concerto\database\{
    MailCcInf,
    MailCcInfData,
    MailInf
};
use Concerto\mail\MailClientDataBuildFactoryInterface;
use Concerto\standard\{
    Session,
    ViewStandard,
};

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
    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    public function getMailCcInf(): MailCcInf
    {
        return new MailCcInf($this->pdo);
    }

    public function getMailCcInfData(): MailCcInfData
    {
        return new MailCcInfData();
    }

    public function getMailInf(): MailInf
    {
        return new MailInf($this->pdo);
    }

    public function getSession(
        ?string $namespace = null
    ): Session {
        return new Session($namespace);
    }

    public function getViewStandard(): ViewStandard
    {
        return new ViewStandard();
    }
}
