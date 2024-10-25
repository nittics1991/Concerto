<?php

declare(strict_types=1);

namespace candidate_test\pattern\data\FilesystemIteratorSubject;

use SplObserver;
use SplSubject;

class Delta implements SplObserver
{
    public function update(SplSubject $subject): void
    {
        get_class($this);
    }
}
