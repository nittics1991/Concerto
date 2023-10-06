<?php

declare(strict_types=1);

namespace test\Concerto\container;

use Concerto\container\provider\AbstractServiceProvider;

class TestServiceProvider2 extends AbstractServiceProvider
{
    protected $provides = [
      'database.dns',
      \PDO::class
    ];

    public function register()
    {
        $this->share('database.dns', 'sqlite::memory:');

        $this->share(\PDO::class, function ($container) {
            return new \PDO(
                $container->get('database.dns')
            );
        });
    }
}
