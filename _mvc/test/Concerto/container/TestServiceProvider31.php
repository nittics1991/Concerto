<?php

declare(strict_types=1);

namespace test\Concerto\container;

use Concerto\container\provider\AbstractServiceProvider;
use Concerto\container\provider\BootableServiceProviderInterface;

class TestServiceProvider31 extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    protected $provides = [
      \PDO::class
    ];

    public function register()
    {
    }

    public function boot()
    {
        $pdo = new \PDO($this->getContainer()->get('database.dns'));
        $this->share(\PDO::class, $pdo);
    }
}
