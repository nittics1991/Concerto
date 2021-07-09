<?php

use DateTimeInterface;
use DatePointToPointInterface;

class ValidPeriod implements DatePointToPointInterface
{
    private $start;

    private $end;

    public function __construct(
        DateTimeInterface $start,
        DateTimeInterface $end
    ) {
        if ($end > $start) {
            $this->start = $start;
            $this->end = $end;
            return;
        }

        $this->end = $start;
        $this->start = $end;
    }

    public function start()
    {
        return $this->start;
    }

    public function end()
    {
        return $this->end;
    }


    public function greaterOfStart(DateTimeInterface $target)
    {
    }


    public function within(
        DateTimeInterface $target,
        bool $containsStart,
        bool $containsEnd
    ) {
    }

    public function without(
        DateTimeInterface $target,
        bool $contains
    ): bool;
}
