<?php

/**
*   factory
*
*   @version 240826
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

/**
*   @template TValue
*   @implements MailClientDataBuildFactoryInterface<TValue>
*/
class MailClientDataBuildFactory implements MailClientDataBuildFactoryInterface
{
    /**
    *   @var PDO
    */
    private PDO $pdo;

    /**
    *   __construct
    *
    *   @param PDO $pdo
    */
    public function __construct(
        PDO $pdo
    ) {
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

    /**
    *   @return Session<mixed>
    */
    public function getSession(
        bool $global = false
    ): Session {
        return $global ?
            new Session() :
            new Session('mailClient');
    }

    /**
    *   @return ViewStandard<mixed>
    */
    public function getViewStandard(): ViewStandard
    {
        return new ViewStandard();
    }
}
