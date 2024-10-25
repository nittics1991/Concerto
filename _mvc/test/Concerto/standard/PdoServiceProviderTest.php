<?php

declare(strict_types=1);

namespace test\Concerto\standard;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
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
        if (
            !isset($_SERVER['USERDOMAIN']) ||
            !mb_ereg_match('toshiba', $_SERVER['USERDOMAIN'], 'i')
        ) {
            $this->markTestSkipped('things to do in the company');
            return;
        }

        $this->container = new ServiceContainer();
        $this->container->delegate(new ServiceProviderContainer());
        $this->container->addServiceProvider(PdoServiceProvider::class);

        global $DB_DSN;
        global $DB_USER;
        global $DB_PASSWD;
        global $DB_DBNAME;
        global $SYMPHONY_DSN;
        global $SYMPHONY_USER;
        global $SYMPHONY_PASSWD;

        $this->config =
            [
                'database' => [
                    'default' => [
                        'dns' => $DB_DSN,
                        'user' => $DB_USER,
                        'password' => $DB_PASSWD
                    ],
                    'Symphony' => [
                        'dns' => $SYMPHONY_DSN,
                        'user' => $SYMPHONY_USER,
                        'password' => $SYMPHONY_PASSWD
                    ],
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

        $this->assertInstanceOf(PDO::class, $this->container->get('concertoPdo'));
        $this->assertInstanceOf(PDO::class, $this->container->get('symphonyPdo'));
    }
}
