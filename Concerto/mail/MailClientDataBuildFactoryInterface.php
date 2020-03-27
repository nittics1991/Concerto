<?php

/**
*   factoryインターフェース
*
*   @version 151110
*/

declare(strict_types=1);

namespace Concerto\mail;

interface MailClientDataBuildFactoryInterface
{
    /**
    *   DI
    */
    public function getPdo();
    public function getMailCcInf();
    public function getMailCcInfData();
    public function getMailInf();
    public function getSession();
    public function getViewStandard();
}
