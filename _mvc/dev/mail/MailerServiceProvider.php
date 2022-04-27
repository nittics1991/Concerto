<?php

/**
*   MailerServiceProvider
*
*   @version 210902
*/

declare(strict_types=1);

namespace dev\mail;

use dev\container\provider\AbstractServiceProvider;
use dev\mail\{
    MailSwiftSmtp,
    MailTransferInterface,
    RedundantSmtpServer
};

class MailerServiceProvider extends AbstractServiceProvider
{
    /**
    *   @var array
    */
    protected $provides = [
      MailTransferInterface::class,
      RedundantSmtpServer::class,
    ];

    /**
    *   register
    *
    */
    public function register(): void
    {
        $this->share(
            RedundantSmtpServer::class,
            function ($container) {
                $config = $container->get('configSystem');
                $smtp = [];

                foreach ($config['smtp'] as $val) {
                    $smtp[] = new MailSwiftSmtp($val);
                }
                return new RedundantSmtpServer($smtp);
            }
        );

        $this->share(
            MailTransferInterface::class,
            function ($container) {
                return $container->get(RedundantSmtpServer::class);
            }
        );
    }
}
