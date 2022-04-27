<?php

/**
*   MailTransferInterface
*
*   @version 210903
*/

declare(strict_types=1);

namespace dev\mail;

use dev\mail\MailMessage;

interface MailTransferInterface
{
    /**
    *   send
    *
    *   @param MailMessage $messages
    */
    public function send(
        MailMessage $messages
    ):mixed;
}
