<?php

/**
*   MailTransferInterface
*
*   @version 221207
*/

declare(strict_types=1);

namespace Concerto\mail;

interface MailTransferInterface
{
    /**
    *   送信
    *
    *   @param mixed $mails
    */
    public function send($mails);
}
