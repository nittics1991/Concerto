<?php

/**
*   YearMonth
*
*   @version 170317
*/

namespace Concerto\datetime;

use DateTimeImmutable;
use Concerto\datetime\DateTimeBase;

class YearMonth extends DateTimeBase
{
    /**
    *   {inherit}
    *
    **/
    public function __construct($time = 'now', $timezone = null)
    {
        if (isset($time) && mb_ereg_match('\A\d{6}\z', $time)) {
            $time = mb_substr($time, 0, 4) . '-' . mb_substr($time, 4, 2);
        }
        
        $original = new DateTimeImmutable($time, $timezone);
        $time = $original->format('Ym01 000000');
        parent::__construct($time, $timezone);
    }
    
    /**
    *   {inherit}
    *
    **/
    public function toString()
    {
        return $this->format('Ym');
    }
}
