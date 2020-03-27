<?php

/**
*   DateObject
*
*   @version 170301
*/

namespace Concerto\datetime;

use DateTimeImmutable;
use Concerto\datetime\DateTimeBase;

class DateObject extends DateTimeBase
{
    /**
    *   construct
    *
    *   @param string
    *   @param DateTimeZone
    **/
    public function __construct($time = 'now', $timezone = null)
    {
        $original = new DateTimeImmutable($time, $timezone);
        $time = $original->format('Ymd 000000');
        parent::__construct($time, $timezone);
    }
    
    /**
    *   {inherit}
    *
    **/
    public function toString()
    {
        return $this->format('Ymd');
    }
}
