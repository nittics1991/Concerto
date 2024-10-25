<?php

/**
*   factoryインターフェース
*
*   @version 240826
*/

declare(strict_types=1);

namespace Concerto\mail;

use PDO;
use Concerto\database\{
    MailCcInf,
    MailCcInfData,
    MailInf,
};
use Concerto\standard\{
    Session,
    ViewStandard,
};

/**
*   @template TValue
*/
interface MailClientDataBuildFactoryInterface
{
    /**
    *   getPdo
    *
    *   @return PDO
    */
    public function getPdo(): PDO;

    /**
    *   getMailCcInf
    *
    *   @return MailCcInf
    */
    public function getMailCcInf(): MailCcInf;

    /**
    *   getMailCcInfData
    *
    *   @return MailCcInfData
    */
    public function getMailCcInfData(): MailCcInfData;

    /**
    *   getMailInf
    *
    *   @return MailInf
    */
    public function getMailInf(): MailInf;

    /**
    *   getSession
    *
    *   @return Session<TValue>
    */
    public function getSession(): Session;

    /**
    *   getViewStandard
    *
    *   @return ViewStandard<TValue>
    */
    public function getViewStandard(): ViewStandard;
}
