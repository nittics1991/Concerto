<?php

declare(strict_types=1);

namespace test\Concerto\pattern\data\FilesystemIteratorSubject;

use SplObserver;
use SplSubject;

class Delta implements SplObserver
{
    public function update(SplSubject $subject)
    {
        return get_class($this);
    }
}
