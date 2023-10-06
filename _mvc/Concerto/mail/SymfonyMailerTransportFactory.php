<?php

/**
*   SymfonyMailer SMTP transport factory
*
*       NativeTransportFactoryではphp.ini設定を使用するため改造
*
*   @see https://github.com/symfony/mailer/blob/5.4/
*       Transport/NativeTransportFactory.php
*
*   @version 221207
*/

declare(strict_types=1);

namespace Concerto\mail;

use Symfony\Component\Mailer\Exception\{
    TransportException,
    UnsupportedSchemeException
};
use Symfony\Component\Mailer\Transport\{
    AbstractTransportFactory,
    Dsn
};
use Symfony\Component\Mailer\Transport\Smtp\SmtpTransport;
use Symfony\Component\Mailer\Transport\Smtp\Stream\SocketStream;
use Symfony\Component\Mailer\Transport\TransportInterface;

final class SymfonyMailerTransportFactory extends AbstractTransportFactory
{
    /**
    *   create
    *
    *   @param Dsn $dsn
    *   @return TransportInterface
    */
    public function create(Dsn $dsn): TransportInterface
    {
        if (
            !\in_array(
                $dsn->getScheme(),
                $this->getSupportedSchemes(),
                true
            )
        ) {
            throw new UnsupportedSchemeException(
                $dsn,
                'native',
                $this->getSupportedSchemes()
            );
        }

/*
        if ($sendMailPath = ini_get('sendmail_path')) {
            return new SendmailTransport(
                $sendMailPath,
                $this->dispatcher,
                $this->logger
            );
        }

        if ('\\' !== \DIRECTORY_SEPARATOR) {
            throw new TransportException(
                'sendmail_path is not configured in php.ini.'
            );
        }

        // Only for windows hosts; at this point non-windows
        // host have already thrown an exception or returned a transport
        $host = ini_get('SMTP');
        $port = (int) ini_get('smtp_port');
*/

        $host = $dsn->getHost();

        $port = (int) $dsn->getPort();

        if (!$host || !$port) {
            // throw new TransportException(
                // 'smtp or smtp_port is not configured in php.ini.'
            // );
            throw new TransportException(
                'smtp or smtp_port is not configured in DNS'
            );
        }

        $socketStream = new SocketStream();

        $socketStream->setHost($host);

        $socketStream->setPort($port);

        if (465 !== $port) {
            $socketStream->disableTls();
        }

        return new SmtpTransport(
            $socketStream,
            $this->dispatcher,
            $this->logger
        );
    }

    /**
    *   getSupportedSchemes
    *
    *   @return string[]
    */
    protected function getSupportedSchemes(): array
    {
        return ['native'];
    }
}
