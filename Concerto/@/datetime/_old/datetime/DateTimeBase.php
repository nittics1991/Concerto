<?php

/**
*   DateTimeBase
*
*   @version 170307
*/

namespace Concerto\datetime;

use DateInterval;
use DateTimeImmutable;
use InvalidArgumentException;
use Concerto\datetime\DateTimeBaseInterface;

abstract class DateTimeBase extends DateTimeImmutable implements DateTimeBaseInterface
{
    /**
    *   {inherit}
    *
    **/
    abstract public function toString();
    
    /**
    *   {inherit}
    *
    **/
    public function __toString()
    {
        return $this->toString();
    }
    
    /**
    *   {inherit}
    *
    **/
    public function nextMonth($month = 1)
    {
        if (!is_int($month)) {
            throw new InvalidArgumentException("must be type int");
        }
        $day = (int)$this->format('j');
        
        if ($month >= 0) {
            $date = $this->add(new DateInterval("P{$month}M"));
        } else {
            $month *= -1;
            $date = $this->sub(new DateInterval("P{$month}M"));
        }
        
        $nextDay = (int)$date->format('j');
        if ($nextDay != $day) {
            return $date->sub(new DateInterval("P{$nextDay}D"));
        }
        return $date;
    }
    
    /**
    *   {inherit}
    *
    **/
    public function lastMonth($month = 1)
    {
        if (!is_int($month)) {
            throw new InvalidArgumentException("must be type int");
        }
        return $this->nextMonth(-1 * $month);
    }
    
    /**
    *   {inherit}
    *
    **/
    public function nextYear($year = 1)
    {
        if (!is_int($year)) {
            throw new InvalidArgumentException("must be type int");
        }
        $day = (int)$this->format('j');
        
        if ($year >= 0) {
            $date = $this->add(new DateInterval("P{$year}Y"));
        } else {
            $year *= -1;
            $date = $this->sub(new DateInterval("P{$year}Y"));
        }
        
        $nextDay = (int)$date->format('j');
        if ($nextDay != $day) {
            return $date->sub(new DateInterval("P{$nextDay}D"));
        }
        return $date;
    }
    
    /**
    *   {inherit}
    *
    **/
    public function lastYear($year = 1)
    {
        if (!is_int($year)) {
            throw new InvalidArgumentException("must be type int");
        }
        return $this->nextYear(-1 * $year);
    }
    
    /**
    *   {inherit}
    *
    **/
    public function isEndOfMonth()
    {
        $date = clone $this;
        return $this == $date->modify('last day of this month');
    }
    
    /**
    *   {inherit}
    *
    **/
    public function isLeapYear()
    {
        return (bool)$this->format('L');
    }
    
    /**
    *   {inherit}
    *
    **/
    public function isToday()
    {
        return $this->format('Ymd') == (new DateTimeImmutable())->format('Ymd');
    }
    
    /**
    *   {inherit}
    *
    **/
    public function isPastDay()
    {
        return $this->format('Ymd') < (new DateTimeImmutable())->format('Ymd');
    }
    
    /**
    *   {inherit}
    *
    **/
    public function isFutureDay()
    {
        return $this->format('Ymd') > (new DateTimeImmutable())->format('Ymd');
    }
    
    /**
    *   {inherit}
    *
    **/
    public function isThisMonth()
    {
        return $this->format('Ym') == (new DateTimeImmutable())->format('Ym');
    }
    
    /**
    *   {inherit}
    *
    **/
    public function isPastMonth()
    {
        return $this->format('Ym') < (new DateTimeImmutable())->format('Ym');
    }
    
    /**
    *   {inherit}
    *
    **/
    public function isFutureMonth()
    {
        return $this->format('Ym') > (new DateTimeImmutable())->format('Ym');
    }
    
    /**
    *   {inherit}
    *
    **/
    public function isThisYear()
    {
        return $this->format('Y') == (new DateTimeImmutable())->format('Y');
    }
    
    /**
    *   {inherit}
    *
    **/
    public function isPastYear()
    {
        return $this->format('Y') < (new DateTimeImmutable())->format('Y');
    }
    
    /**
    *   {inherit}
    *
    **/
    public function isFutureYear()
    {
        return $this->format('Y') > (new DateTimeImmutable())->format('Y');
    }
}
