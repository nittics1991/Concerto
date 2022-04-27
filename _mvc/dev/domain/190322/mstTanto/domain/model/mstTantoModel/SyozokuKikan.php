<?php

use ValidPeriod;

class SyozokuKikan extends ValidPeriod
{
    public function __construct(
        DateTimeInterface $start,
        DateTimeInterface $end
    ) {
        $start = new DateObject($start->format(DateTime::ISO8601));
        $end = new DateObject($end->format(DateTime::ISO8601));
        return parent::__construct($start, $end);
    }
}
