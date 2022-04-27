<?php

declare(strict_types=1);

namespace test\Concerto\container\directory;

class ProviderTarget2
{
    public function __invoke()
    {
        return __CLASS__;
    }
}
