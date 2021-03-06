<?php

declare(strict_types=1);

namespace Concerto\test\pattern\data\FilesystemIteratorSubject;

use SplObserver;
use SplSubject;

class Beta implements SplObserver
{
    public function update(SplSubject $subject)
    {
        return get_class($this);
    }
}
