<?php

use DateTimeInterface;

interface DatePointToPointInterface
{
    public function greaterOfStart(DateTimeInterface $target);
    public function greaterThanOfStart(DateTimeInterface $target);
    public function lessOfStart(DateTimeInterface $target);
    public function lessThanOfStart(DateTimeInterface $target);

    public function greaterOfEnd(DateTimeInterface $target);
    public function greaterThanOfEnd(DateTimeInterface $target);
    public function lessOfEnd(DateTimeInterface $target);
    public function lessThanOfEnd(DateTimeInterface $target);

    public function within(
        DateTimeInterface $target,
        bool $containsStart,
        bool $containsEnd
    ): bool;

    public function without(
        DateTimeInterface $target,
        bool $containsStart,
        bool $containsEnd
    ): bool;
}
