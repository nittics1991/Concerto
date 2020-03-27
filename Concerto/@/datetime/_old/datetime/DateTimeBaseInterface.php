<?php

/**
*   DateTimeBaseInterface
*
*   @version 170307
*/

namespace Concerto\datetime;

use DateTimeInterface;

interface DateTimeBaseInterface extends DateTimeInterface
{
    /**
    *   toString
    *
    *   @return string
    **/
    public function toString();
    
    /**
    *   nextMonth
    *
    *   @param DateTimeInterface
    *   @example 2016-1-31 => 2016-2-29
    **/
    public function nextMonth($month);
    
    /**
    *   lastMonth
    *
    *   @param DateTimeInterface
    *   @example 2016-3-31 => 2016-2-29
    **/
    public function lastMonth($month);
    
    /**
    *   nextYear
    *
    *   @param DateTimeInterface
    *   @example 2016-2-29 => 2017-2-28
    **/
    public function nextYear($year);
    
    /**
    *   lastYear
    *
    *   @param DateTimeInterface
    *   @example 2016-2-29 => 2015-2-28
    **/
    public function lastYear($year);
    
    /**
    *   isEndOfMonth
    *
    *   @return bool
    **/
    public function isEndOfMonth();
    
    /**
    *   isLeapYear
    *
    *   @return bool
    **/
    public function isLeapYear();
    
    /**
    *   isToday
    *
    *   @return bool
    **/
    public function isToday();
    
    /**
    *   isPastDay
    *
    *   @return bool
    **/
    public function isPastDay();
    
    /**
    *   isFutureDay
    *
    *   @return bool
    **/
    public function isFutureDay();
    
    /**
    *   isThisMonth
    *
    *   @return bool
    **/
    public function isThisMonth();
    
    /**
    *   isPastMonth
    *
    *   @return bool
    **/
    public function isPastMonth();
    
    /**
    *   isFutureMonth
    *
    *   @return bool
    **/
    public function isFutureMonth();
    
    /**
    *   isThisYear
    *
    *   @return bool
    **/
    public function isThisYear();
    
    /**
    *   isPastYear
    *
    *   @return bool
    **/
    public function isPastYear();
    
    /**
    *   isFutureYear
    *
    *   @return bool
    **/
    public function isFutureYear();
}
