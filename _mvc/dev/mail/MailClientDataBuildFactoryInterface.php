<?php

/**
*   factoryインターフェース
*
*   @version 210902
*/

declare(strict_types=1);

namespace dev\mail;

use PDO;
use dev\database\{
    MailCcInf,
    MailCcInfData,
    MailInf,
};
use dev\standard\{
    Session,
    ViewStandard,
};

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
    *   @return Session
    */
    public function getSession(): Session;

    /**
    *   getViewStandard
    *
    *   @return ViewStandard
    */
    public function getViewStandard(): ViewStandard;
}
