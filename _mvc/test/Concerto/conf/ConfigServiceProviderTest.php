<?php

declare(strict_types=1);

namespace test\Concerto\conf;

use test\Concerto\ConcertoTestCase;
use Concerto\container\ServiceContainer;
use Concerto\container\ServiceProviderContainer;
use Concerto\conf\ConfigServiceProvider;
use Concerto\conf\Config;

class ConfigServiceProviderTest extends ConcertoTestCase
{
    private $container;

    public function setUp(): void
    {
        if (
            !isset($_SERVER["OS"]) ||
            stripos($_SERVER["OS"], 'WINDOWS') === false
        ) {
            $this->markTestSkipped('Windows上でのみテスト実行');
            return;
        }
        $this->container = new ServiceContainer();
        $this->container->delegate(new ServiceProviderContainer());
        $this->container->addServiceProvider(ConfigServiceProvider::class);
    }

    /**
    *   @test
    */
    public function getObject()
    {
//       $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertInstanceOf(Config::class, $this->container->get('configSystem'));
    }
}
