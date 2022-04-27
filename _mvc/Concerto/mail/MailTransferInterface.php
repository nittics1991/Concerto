<?php

/**
*   MailTransferInterface
*
*   @version 170116
*/

declare(strict_types=1);

namespace Concerto\mail;

interface MailTransferInterface
{
    /**
    *   送信
    *
    *   @param mixed $mails
    *   @rerurn bool
    */
    public function send($mails);
}
