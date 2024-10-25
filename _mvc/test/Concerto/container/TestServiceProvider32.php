<?php

declare(strict_types=1);

namespace test\Concerto\container;

use Concerto\container\provider\AbstractServiceProvider;

class TestServiceProvider32 extends AbstractServiceProvider
{
    protected $provides = [
      'database.dns',
    ];

    public function register()
    {
        $this->share('database.dns', 'sqlite::memory:');
    }
}
