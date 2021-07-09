<?php

declare(strict_types=1);

namespace Concerto\test\conf;

use Concerto\test\ConcertoTestCase;
use Concerto\container\ServiceContainer;
use Concerto\container\ServiceProviderContainer;
use Concerto\conf\ConfigServiceProvider;
use Concerto\conf\Config;

class ConfigServiceProviderTest extends ConcertoTestCase
{
    private $container;

    public function setUp(): void
    {
        $this->container = new ServiceContainer();
        $this->container->delegate(new ServiceProviderContainer());
        $this->container->addServiceProvider(ConfigServiceProvider::class);
    }

    /**
    *   @test
    */
    public function getObject()
    {
//       $this->markTestIncomplete();

        $this->assertInstanceOf(Config::class, $this->container->get('configSystem'));
    }
}
