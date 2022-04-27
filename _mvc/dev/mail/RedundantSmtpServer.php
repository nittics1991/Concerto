<?php

/**
*   冗長化SMTPサーバ
*
*   @version 210902
*/

declare(strict_types=1);

namespace dev\mail;

use Exception;
use InvalidArgumentException;
use RuntimeException;
use dev\mail\{
    MailMessage,
    MailTransferInterface
};

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
    public function __construct(array $servers = [])
    {
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
    ): static  {
        $this->servers[] = $server;
        return $this;
    }

    /**
    *   {inherit}
    *
    *   @return self
    */
    public function send(
        MailMessage $messages
    ):self {
        if (!$messages->isValid()) {
            throw new InvalidArgumentException(
                "mail messages error\n" .
                var_export($messages, true)
            );
        }

        foreach ($this->servers as $server) {
            try {
                $sent = $server->send($messages);
                if ($sent) {
                    return $this;
                }
            } catch (Exception $e) {
                //nop
            }
        }
        
        throw new RuntimeException(
            "mail send error\n" . 
            var_export($messages, true)
        );
      return $this;
  }
}
