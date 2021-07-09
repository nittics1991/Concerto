<?php

declare(strict_types=1);

namespace Concerto\test\standard;

use Concerto\test\ConcertoTestCase;
use Concerto\container\ServiceContainer;
use Concerto\container\ServiceProviderContainer;
use Concerto\standard\PdoServiceProvider;
use PDO;

class PdoServiceProviderTest extends ConcertoTestCase
{
    private $container;
    private $config;

    public function setUp(): void
    {
        $this->container = new ServiceContainer();
        $this->container->delegate(new ServiceProviderContainer());
        $this->container->addServiceProvider(PdoServiceProvider::class);

        $this->config =
            [
                'database' => [
                    'default' => [
                        'dns' => 'pgsql:host=localhost; port=5430; dbname=postgres;',
                        'user' => 'concerto',
                        'password' => 'manager'
                    ],
                    'Symphony' => [
                        'dns' => 'oci:dbname=ITCA;',
                        'user' => 'ITC_USER',
                        'password' => 'ITC_201304'
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
//       $this->markTestIncomplete();

        $this->assertInstanceOf(PDO::class, $this->container->get('concertoPdo'));
        $this->assertInstanceOf(PDO::class, $this->container->get('symphonyPdo'));
    }
}
