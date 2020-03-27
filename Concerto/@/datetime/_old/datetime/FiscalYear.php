<?php

/**
*   FiscalYear
*
*   @version 170317
*/

namespace Concerto\datetime;

use DateTimeImmutable;
use Concerto\datetime\DateTimeBase;

class FiscalYear extends DateTimeBase
{
    /**
    *   {inherit}
    *
    **/
    public function __construct($time = 'now', $timezone = null)
    {
        if (isset($time) && mb_ereg_match('\A\d{4}(K|S)\z', $time)) {
            $year = mb_substr($time, 0, 4);
            $month = (mb_substr($time, 4, 1) == 'K') ? '04' : '10';
        } else {
            $original = new DateTimeImmutable($time, $timezone);
            $year = $original->format('Y');
            $month = (int)$original->format('n');
            $month = ($month >= 4 && $month <= 9) ? '04' : '10';
        }
        
        $time = "{$year}-{$month}-01 00:00:00";
        parent::__construct($time, $timezone);
    }
    
    /**
    *   {inherit}
    *
    **/
    public function toString()
    {
        return ($this->format('n') == 4) ?
            $this->format('Y') . 'K'
            : $this->format('Y') . 'S';
    }
}
