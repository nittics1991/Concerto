<?php

/**
*   SymfonyMailer SMTP
*
*   @version 220301
*/

declare(strict_types=1);

namespace Concerto\mail;

use InvalidArgumentException;
use Concerto\mail\MailTransferInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Transport\{
    Dsn,
    NativeTransportFactory,
};
use Symfony\Component\Mime\{
    Address,
    Email,
};

class MailSymfonySmtp implements MailTransferInterface
{
    /**
    *   設定データ
    *
    *   @var mixed[]
    */
    private array $params = [
        'host' => '127.0.0.1',
        'port' => 25,
        'user' => null,
        'password' => null,
    ];

    /**
    *   @var Mailer
    */
    private Mailer $mailer;

    /**
    *   __construct
    *
    *   @param mixed[] $params 設定値
    */
    public function __construct(
        array $params = []
    ) {
        $this->setMailer($params);
    }

    /**
    *   パラメータ設定
    *
    *   @param mixed[] $params [id => 値]
    */
    private function setMailer(
        array $params = []
    ): void {
        if (array_diff_key($params, $this->params) !== []) {
            throw new InvalidArgumentException(
                "invalid parameters"
            );
        }

        if (
            isset($params['host']) &&
            is_string($params['host'])
        ) {
            $this->params['host'] = $params['host'];
        }

        if (
            isset($params['port']) &&
            is_int($params['port'])
        ) {
            $this->params['port'] = $params['port'];
        }

        if (
            isset($params['user']) &&
            is_string($params['user'])
        ) {
            $this->params['user'] = $params['user'];
        }

        if (
            isset($params['password']) &&
            is_string($params['password'])
        ) {
            $this->params['password'] = $params['password'];
        }

        $transport = (new NativeTransportFactory())
            ->create(
                new Dsn(
                    'native',
                    $this->params['host'],
                    $this->params['user'],
                    $this->params['password'],
                    $this->params['port'],
                ),
            );

        $this->mailer = new Mailer($transport);
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

        $email = new Email();

        $from = $this->buildAddress(
            $message->from ?? [],
        );

        $email->from($from[0]);

        $to = $this->buildAddress(
            $message->to ?? [],
        );

        call_user_func_array(
            [$email, 'to'],
            $to,
        );

        $cc = $this->buildAddress(
            $message->cc ?? [],
        );

        call_user_func_array(
            [$email, 'cc'],
            $cc,
        );

        $bcc = $this->buildAddress(
            $message->bcc ?? [],
        );

        call_user_func_array(
            [$email, 'bcc'],
            $bcc,
        );

        $email = $email->subject($message->subject);

        if ($message->type === 'html') {
            $email->html($message->message);
        } else {
            $email->text($message->message);
        }

        foreach ((array)$message->attach as $params) {
            if (isset($params['file'])) {
                $email->attachFromPath($params['file']);
            }
        }

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            return $e;
        }
        return true;
    }

    /**
    *   buildAddress
    *
    *   @param string $param
    *   @return Address[]
    */
    private function buildAddress(
        array $addrsses,
    ): array {
        $result = [];

        foreach ($addrsses as $address => $display_name) {
            $result[] = new Address(
                $address,
                $display_name
            );
        }
        return $result;
    }
}
