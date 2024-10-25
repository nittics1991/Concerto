<?php

/**
*   冗長化SMTPサーバ
*
*   @version 221207
*/

declare(strict_types=1);

namespace Concerto\mail;

use Exception;
use InvalidArgumentException;
use Concerto\mail\MailMessage;
use Concerto\mail\MailTransferInterface;

class RedundantSmtpServer implements MailTransferInterface
{
    /**
    *   @var MailTransferInterface[]
    */
    private array $servers = [];

    /**
    *   __construct
    *
    *   @param MailTransferInterface[] $servers
    */
    public function __construct(
        array $servers = []
    ) {
        foreach ($servers as $server) {
            $this->add($server);
        }
    }

    /**
    *   追加
    *
    *   @param MailTransferInterface $server
    */
    public function add(
        MailTransferInterface $server
    ): void {
        $this->servers[] = $server;
    }

    /**
    *   送信
    *
    *   @param MailMessage $mail
    *   @return ?MailMessage 失敗したMailMessage
    */
    public function send(
        mixed $mail
    ): ?MailMessage {
        $current = 0;

        $result = null;

        $sent = false;

        if (!$mail instanceof MailMessage) {
            throw new InvalidArgumentException(
                "required MailMessage"
            );
        }

        while (count($this->servers) > $current) {
            try {
                if (
                    $sent = $this->servers[$current]->send($mail)
                ) {
                    break;
                }
            } catch (Exception $e) {
                $sent = false;
            }

            $current++;
        }

        if (!$sent) {
            $result = $mail;
        }

        return $result;
    }
}
