<?php

declare(strict_types=1);

namespace test\Concerto\log;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\container\ServiceContainer;
use Concerto\container\ServiceProviderContainer;
use Concerto\log\LogServiceProvider;
use Concerto\log\Log;
use Concerto\log\LogInterface;
use Concerto\log\LogWriterErrorLog;
use Concerto\log\LogWriterInterface;

class LogServiceProviderTest extends ConcertoTestCase
{
    private $container;
    private $config;

    public function setUp(): void
    {
        $this->container = new ServiceContainer();
        $this->container->delegate(new ServiceProviderContainer());
        $this->container->addServiceProvider(LogServiceProvider::class);

        $this->config =
            [
                'log' => [
                    'default' => [
                        'stream' => 'err.log',
                        'format' => '%s:%s' . PHP_EOL
                    ]
                ]
            ];
        $this->container->bind('configSystem', $this->config);
    }

    /**
    */
    #[Test]
    public function getObject()
    {
//       $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($this->config, $this->container->get('configSystem'));
        $this->assertInstanceOf(LogWriterErrorLog::class, $this->container->get('Concerto\log\LogWriterErrorLog'));
        $this->assertInstanceOf(LogWriterErrorLog::class, $this->container->get(LogWriterErrorLog::class));
        $this->assertInstanceOf(Log::class, $this->container->get(Log::class));
        $this->assertInstanceOf(LogWriterErrorLog::class, $this->container->get(LogWriterInterface::class));
        $this->assertInstanceOf(Log::class, $this->container->get(LogInterface::class));
    }
}
