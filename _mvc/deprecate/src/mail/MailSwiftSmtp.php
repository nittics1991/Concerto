<?php

/**
*   SwiftMailer SMTP
*
*   @version 210903
*/

declare(strict_types=1);

namespace Concerto\mail;

use InvalidArgumentException;
use Swift_Attachment;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Concerto\mail\MailTransferInterface;

class MailSwiftSmtp implements MailTransferInterface
{
    /**
    *   設定データ
    *
    *   @var mixed[]
    */
    private $params = [
        'host' => '127.0.0.1',
        'port' => 25,
        'user' => null,
        'password' => null,
        'security' => null
    ];

    /**
    *   mailer
    *
    *   @var Swift_Mailer
    */
    private $mailer;

    /**
    *   __construct
    *
    *   @param mixed[] $params 設定値
    */
    public function __construct(array $params = [])
    {
        $this->setMailer($params);
    }

    /**
    *   パラメータ設定
    *
    *   @param mixed[] $params [id => 値]
    */
    private function setMailer(array $params = []): void
    {
        if (array_diff_key($params, $this->params) != []) {
            throw new InvalidArgumentException("invalid parameters");
        }

        if (isset($params['host']) && is_string($params['host'])) {
            $this->params['host'] = $params['host'];
        }

        if (isset($params['port']) && is_int($params['port'])) {
            $this->params['port'] = $params['port'];
        }

        if (isset($params['user'])) {
            $this->params['user'] = $params['user'];
        }

        if (isset($params['password'])) {
            $this->params['password'] = $params['password'];
        }

        if (isset($params['security']) && is_string($params['security'])) {
            $this->params['security'] = $params['security'];
        }

        $transport = new Swift_SmtpTransport(
            $this->params['host'],
            $this->params['port'],
            $this->params['security']
        );

        if (isset($this->params['user'])) {
            $transport->setUsername($this->params['user']);
            $transport->setPassword($this->params['password']);
        }
        $this->mailer = new Swift_Mailer($transport);
    }

    /**
    *   送信
    *
    *   @param MailMessage $message
    *   @return bool|object 失敗したMailMessage
    */
    public function send($message)
    {
        if (
            !$message instanceof MailMessage ||
            !$message->isValid()
        ) {
            return $message;
        }

        $object = new Swift_Message($message->subject);
        $object
            ->setFrom($message->from)
            ->setTo($message->to)
            ->setCc($message->cc)
            ->setBcc($message->bcc)
        ;

        if ($message->type == 'html') {
            $object->setBody($message->message, 'text/html');
        } else {
            $object->setBody($message->message, 'text/plain');
        }

        foreach ((array)$message->attach as $params) {
            if (isset($params['file'])) {
                $object->attach(Swift_Attachment::fromPath($params['file']));
            }
        }

        if (!$this->mailer->send($object)) {
            return $message;
        }
        return true;
    }
}
