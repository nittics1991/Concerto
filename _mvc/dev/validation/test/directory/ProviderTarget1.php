<?php

declare(strict_types=1);

namespace test\Concerto\container\directory;

class ProviderTarget1
{
    public function __invoke()
    {
        return __CLASS__;
    }
}
