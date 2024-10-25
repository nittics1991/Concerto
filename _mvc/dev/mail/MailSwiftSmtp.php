<?php

/**
*   SwiftMailer SMTP
*
*   @version 210903
*/

declare(strict_types=1);

namespace dev\mail;

use InvalidArgumentException;
use RuntimeException;
use Swift_Attachment;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use dev\mail\{
    MailMessage,
    MailTransferInterface
};

class MailSwiftSmtp implements MailTransferInterface
{
    /**
    *   @var string
    */
    private string $host;

    /**
    *   @var int
    */
    private int $port;

    /**
    *   @var ?string
    */
    private ?string $user;

    /**
    *   @var ?string
    */
    private ?string $password;

    /**
    *   @var ?string
    */
    private ?string $encryption;

    /**
    *   mailer
    *
    *   @var Swift_Mailer
    */
    private Swift_Mailer $mailer;

    /**
    *   __construct
    *
    *   @param mixed[] $params 設定値
    */
    public function __construct(array $params = [])
    {
        $this->init($params);
    }

    /**
    *   init
    *
    *   @param mixed[] $params
    */
    private function init(array $params = []): void
    {
        $this->host = $params['host'] ?? '127.0.0.1';
        $this->port = $params['port'] ?? 25;
        $this->user = $params['user'] ?? null;
        $this->password = $params['password'] ?? null;
        $this->encryption = $params['encryption'] ?? null;

        $transport = new Swift_SmtpTransport(
            $this->host,
            $this->port,
            $this->encryption
        );

        if (isset($this->user)) {
            $transport->setUsername($this->user);
            $transport->setPassword($this->password);
        }
        $this->mailer = new Swift_Mailer($transport);
    }

    /**
    *   @inheritDoc
    *
    *   @return self
    */
    public function send(
        MailMessage $messages
    ): self {
        if (
            !$messages instanceof MailMessage
            || !$messages->isValid()
        ) {
            throw new InvalidArgumentException(
                "mail messages error" . "\n" .
                print_r($messages, true)
            );
        }

        $object = new Swift_Message($messages->subject);
        $object
            ->setFrom($messages->from)
            ->setTo($messages->to)
            ->setCc($messages->cc)
            ->setBcc($messages->bcc)
        ;

        $object->setBody(
            $messages->message,
            $messages->mimeType()
        );

        foreach ((array)$messages->attach as $params) {
            if (isset($params['file'])) {
                $object->attach(
                    Swift_Attachment::fromPath($params['file'])
                );
            }
        }

        if (!$this->mailer->send($object)) {
            throw new RuntimeException(
                "mail send error\n" .
                print_r($messages, true)
            );
        }
        return $this;
    }

    /**
    *   toArray
    *
    *   @return mixed[]
    */
    public function toArray(): array
    {
        return [
            'host' => $this->host,
            'port' => $this->port,
            'user' => $this->user,
            'password' => $this->password,
            'encryption' => $this->encryption,
        ];
    }
}
