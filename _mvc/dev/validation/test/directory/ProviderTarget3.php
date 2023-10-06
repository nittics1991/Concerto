<?php

declare(strict_types=1);

namespace test\Concerto\container\directory;

class ProviderTarget3
{
    public function __construct($params1 = null, $params2 = null)
    {
        $this->params1 = $params1;
        $this->params2 = $params2;
    }

    public function __invoke()
    {
        return $this->params1 . '_' . $this->params2;
    }
}
