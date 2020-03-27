<?php

declare(strict_types=1);

namespace Concerto\test\container\directory;

class ProviderTarget1
{
    public function __invoke()
    {
        return __CLASS__;
    }
}
