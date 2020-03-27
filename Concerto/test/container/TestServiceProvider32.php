<?php

declare(strict_types=1);

namespace Concerto\test\container;

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
