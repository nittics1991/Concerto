<?php

use DateTimeImmutable;

class Yyyymm extends DateTimeImmutable
{
    private $date;

    public function __construct(string $time, DateTimeZone $timezone)
    {
        $this->date = (new DateTimeImmutable($time, $timezone))
            ->modify('first day of today');
    }

    public function __call(string $name, array $args)
    {
        return call_user_func_array($this->date, $name, $args);
    }

    public static function __callStatic(string $name, array $args)
    {
        return call_user_func_array($this->date, $name, $args);
    }
}
