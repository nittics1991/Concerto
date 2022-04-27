<?php

declare(strict_types=1);

namespace test\Concerto\standard;

use test\Concerto\ConcertoTestCase;
use Concerto\container\ServiceContainer;
use Concerto\container\ServiceProviderContainer;
use Concerto\mail\MailerServiceProvider;
use Concerto\mail\MailTransferInterface;

class MailerServiceProviderTest extends ConcertoTestCase
{
    private $container;
    private $config;

    public function setUp(): void
    {
        $this->container = new ServiceContainer();
        $this->container->delegate(new ServiceProviderContainer());
        $this->container->addServiceProvider(MailerServiceProvider::class);

        $this->config =
            [
                'smtp' => [
                    'default' => [
                        'host' => 'localhost',
                        'port' => 25,
                        'user' => null,
                        'password' => null
                    ],
                    'secondary' => [
                        'host' => 'localhost',
                        'port' => 26,
                        'user' => null,
                        'password' => null
                    ],
                ]
            ];
        $this->container->bind('configSystem', $this->config);
    }

    /**
    *   @test
    */
    public function getObject()
    {
//       $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertInstanceOf(MailTransferInterface::class, $this->container->get(MailTransferInterface::class));
    }
}
