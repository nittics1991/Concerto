<?php

/**
*   DatePeriodObject
*
*   @version 170307
*/

namespace Concerto\datetime;

use DatePeriod;
use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimezone;
use InvalidArgumentException;
use IteratorAggregate;

class DatePeriodObject implements IteratorAggregate
{
    const MONTH_PER_YEAR = 12;
    const DAY_PER_WEEK = 7;
    const SECOND_PER_MINUTE = 60;
    const MINUTE_PER_HOUR = 60;
    
     /**
    *   startDay
    *
    *   @var DateTimeInterface
    **/
    protected $startDay;
    
     /**
    *   endDay
    *
    *   @var DateTimeInterface
    **/
    protected $endDay;
    
     /**
    *   interval
    *
    *   @var DateInterval
    **/
    protected $interval;
    
     /**
    *   callable
    *
    *   @var string
    **/
    protected $callbackArgumentClass = 'DateTimeImmutable';
    
    /**
    *   excludeStartDate
    *
    *   @var bool
    **/
    protected $excludeStartDate = false;
    
    /**
    *   excludeEndDate
    *
    *   @var bool
    **/
    protected $excludeEndDate = false;
    
    /**
    *   construct
    *
    *   @param DateTimeInterface
    *   @param DateTimeInterface
    **/
    public function __construct(
        DateTimeInterface $startDay,
        DateTimeInterface $endDay
    ) {
        $this->startDay = $startDay;
        $this->endDay = $endDay;
        $this->interval = new DateInterval('P1D');
    }
    
    /**
    *   createFiscalYear
    *
    *   @param string
    *   @param object
    *   @return object $this
    *   @throws InvalidArgumentException
    **/
    public static function createFiscalYear($fiscalYear, $timezone = null)
    {
        if (!mb_ereg_match('\A\d{4}(K|k|S|s)\z', $fiscalYear)) {
            throw new InvalidArgumentException("format is YYYYMM(K|S):{$fiscalYear}");
        }
        
        if (isset($timezone) && !($timezone instanceof DateTimezone)) {
            throw new InvalidArgumentException("munst be DateTimezone");
        }
        $year = mb_substr($fiscalYear, 0, 4);
        $half = mb_convert_case(mb_substr($fiscalYear, -1, 1), MB_CASE_UPPER);
        
        if ($half == 'K') {
            $startDay = new DateTimeImmutable("{$year}-04-01 000000", $timezone);
            $endDay = new DateTimeImmutable("{$year}-09-30 235959", $timezone);
        } else {
            $startDay = new DateTimeImmutable("{$year}-10-01 000000", $timezone);
            $year++;
            $endDay = new DateTimeImmutable("{$year}-03-31 235959", $timezone);
        }
        return new static($startDay, $endDay);
    }
    
    /**
    *   createYear
    *
    *   @param int
    *   @param int
    *   @param int
    *   @param object
    *   @return object $this
    *   @throws InvalidArgumentException
    **/
    public static function createYear($year, $month = 1, $interval = 1, $timezone = null)
    {
        if (!is_int($year) || ($year < 1970)  || ($year > 2100)) {
            throw new InvalidArgumentException("yeara must be 1970-2099");
        }
        
        if (!is_int($month) || ($month < 1)  || ($month > 12)) {
            throw new InvalidArgumentException("month must be 1-12");
        }
        
        if (!is_int($interval) || ($interval < 1)) {
            throw new InvalidArgumentException("interval must be int >= 1");
        }
        
        if (isset($timezone) && !($timezone instanceof DateTimezone)) {
            throw new InvalidArgumentException("timezone must be DateTimezone");
        }
        
        $endYear = $year + $interval;
        $startDay = new DateTimeImmutable("{$year}-{$month}-01 000000", $timezone);
        $endDay = new DateTimeImmutable("{$endYear}-{$month}-01 000000", $timezone);
        $endDay = $endDay->modify('-1 second');
        
        return new static($startDay, $endDay);
    }
    
    /**
    *   createMonth
    *
    *   @param int
    *   @param int
    *   @param int
    *   @param object
    *   @return object $this
    *   @throws InvalidArgumentException
    **/
    public static function createMonth($year, $month = 1, $interval = 1, $timezone = null)
    {
        if (!is_int($year) || ($year < 1970)  || ($year > 2100)) {
            throw new InvalidArgumentException("yeara must be 1970-2099");
        }
        
        if (!is_int($month) || ($month < 1)  || ($month > 12)) {
            throw new InvalidArgumentException("month must be 1-12");
        }
        
        if (!is_int($interval) || ($interval < 1)) {
            throw new InvalidArgumentException("interval must be int >= 1");
        }
        
        if (isset($timezone) && !($timezone instanceof DateTimezone)) {
            throw new InvalidArgumentException("timezone must be DateTimezone");
        }
        
        $startDay = new DateTimeImmutable("{$year}-{$month}-01 000000", $timezone);
        
        $endDay = clone $startDay;
        $diff = $interval - 1;
        $endDay = $endDay->add(new DateInterval("P{$diff}M"));
        $endDay = $endDay->modify('last day of this month');
        $endDay = $endDay->setTime(23, 59, 59);
        
        return new static($startDay, $endDay);
    }
    
    /**
    *   createWeek
    *
    *   @param string
    *   @param int
    *   @param object
    *   @return object $this
    *   @throws InvalidArgumentException
    **/
    public static function createWeek($date, $interval = 1, $timezone = null)
    {
        if (!is_int($interval) || ($interval < 1)) {
            throw new InvalidArgumentException("interval must be int >= 1");
        }
        
        if (isset($timezone) && !($timezone instanceof DateTimezone)) {
            throw new InvalidArgumentException("timezone must be DateTimezone");
        }
        
        $startDay = new DateTimeImmutable($date, $timezone);
        $startDay = $startDay->setTime(0, 0, 0);
        
        $endDay = clone $startDay;
        $diff = $interval * 7;
        $endDay = $endDay->add(new DateInterval("P{$diff}D"));
        $endDay = $endDay->modify('-1 second');
        return new static($startDay, $endDay);
    }
    
    /**
    *   createDay
    *
    *   @param string
    *   @param int
    *   @param object
    *   @return object $this
    *   @throws InvalidArgumentException
    **/
    public static function createDay($date, $interval = 1, $timezone = null)
    {
        if (!is_int($interval) || ($interval < 1)) {
            throw new InvalidArgumentException("interval must be int >= 1");
        }
        
        if (isset($timezone) && !($timezone instanceof DateTimezone)) {
            throw new InvalidArgumentException("timezone must be DateTimezone");
        }
        
        $startDay = new DateTimeImmutable($date, $timezone);
        $startDay = $startDay->setTime(0, 0, 0);
        
        $endDay = clone $startDay;
        $diff = $interval;
        $endDay = $endDay->add(new DateInterval("P{$diff}D"));
        $endDay = $endDay->modify('-1 second');
        return new static($startDay, $endDay);
    }
    
    /**
    *   {inherit}
    *
    **/
    public function getIterator()
    {
        $option = ($this->excludeStartDate) ? DatePeriod::EXCLUDE_START_DATE : null;
        
        $period = new DatePeriod(
            $this->startDay,
            $this->interval,
            $this->endDay,
            $option
        );
        
        foreach ($period as $date) {
            yield new $this->callbackArgumentClass(
                $date->format('c'),
                $date->getTimezone()
            );
        }
        
        if (
            (!$this->excludeEndDate)
            && ($date->add($this->interval) == $this->endDay)
        ) {
            yield new $this->callbackArgumentClass(
                $this->endDay->format('c'),
                $this->endDay->getTimezone()
            );
        }
    }
    
    /**
    *   setInterval
    *
    *   @param DateInterval
    *   @retunr object $this
    **/
    public function setInterval(DateInterval $interval)
    {
        $this->interval = $interval;
        return $this;
    }
    
    /**
    *   setIntervalYear
    *
    *   @param int
    *   @retunr object $this
    **/
    public function setIntervalYear($interval)
    {
        if (!is_int($interval)) {
            throw new InvalidArgumentException("must be type int");
        }
        $this->interval = new DateInterval("P{$interval}Y");
        return $this;
    }
    
    /**
    *   setIntervalMonth
    *
    *   @param int
    *   @retunr object $this
    **/
    public function setIntervalMonth($interval)
    {
        if (!is_int($interval)) {
            throw new InvalidArgumentException("must be type int");
        }
        $this->interval = new DateInterval("P{$interval}M");
        return $this;
    }
    
    /**
    *   setIntervalWeek
    *
    *   @param int
    *   @retunr object $this
    **/
    public function setIntervalWeek($interval)
    {
        if (!is_int($interval)) {
            throw new InvalidArgumentException("must be type int");
        }
        $this->interval = new DateInterval("P{$interval}W");
        return $this;
    }
    
    /**
    *   setIntervalDay
    *
    *   @param int
    *   @retunr object $this
    **/
    public function setIntervalDay($interval)
    {
        if (!is_int($interval)) {
            throw new InvalidArgumentException("must be type int");
        }
        $this->interval = new DateInterval("P{$interval}D");
        return $this;
    }
    
    /**
    *   setIntervalHour
    *
    *   @param int
    *   @retunr object $this
    **/
    public function setIntervalHour($interval)
    {
        if (!is_int($interval)) {
            throw new InvalidArgumentException("must be type int");
        }
        $this->interval = new DateInterval("PT{$interval}H");
        return $this;
    }
    
    /**
    *   setIntervalMinute
    *
    *   @param int
    *   @retunr object $this
    **/
    public function setIntervalMinute($interval)
    {
        if (!is_int($interval)) {
            throw new InvalidArgumentException("must be type int");
        }
        $this->interval = new DateInterval("PT{$interval}M");
        return $this;
    }
    
    /**
    *   setIntervalSecond
    *
    *   @param int
    *   @retunr object $this
    **/
    public function setIntervalSecond($interval)
    {
        if (!is_int($interval)) {
            throw new InvalidArgumentException("must be type int");
        }
        $this->interval = new DateInterval("PT{$interval}S");
        return $this;
    }
    
    /**
    *   setCallbackArgumentClass
    *
    *   @param string
    *   @retunr object $this
    **/
    public function setCallbackArgumentClass($callbackArgumentClass)
    {
        if (!class_exists($callbackArgumentClass)) {
            throw new InvalidArgumentException("invalid class name");
        }
        $this->callbackArgumentClass = $callbackArgumentClass;
        return $this;
    }
    
    /**
    *   setExcludeStartDate
    *
    *   @param bool
    *   @return object $this
    **/
    public function setExcludeStartDate($flag = true)
    {
        $this->excludeStartDate = ($flag) ? true : false;
        return $this;
    }
    
    /**
    *   setExcludeEndDate
    *
    *   @param bool
    *   @return object $this
    **/
    public function setExcludeEndDate($flag = true)
    {
        $this->excludeEndDate = ($flag) ? true : false;
        return $this;
    }
    
    /**
    *   diff
    *
    *   @return DateInterval
    **/
    public function diff()
    {
        return $this->startDay->diff($this->endDay);
    }
    
    /**
    *   diffYear
    *
    *   @return int
    **/
    public function diffYear()
    {
        return (int)($this->diffMonth() / static::MONTH_PER_YEAR);
    }
    
    /**
    *   diffMonth
    *
    *   @return int
    **/
    public function diffMonth()
    {
        if ($this->startDay > $this->endDay) {
            $start = clone $this->endDay;
            $end = clone $this->startDay;
            $sign = -1;
        } else {
            $start = clone $this->startDay;
            $end = clone $this->endDay;
            $sign = 1;
        }
        
        $startMonth = (int)$start->format('Y') * 12 + (int)$start->format('n');
        $endMonth = (int)$end->format('Y') * 12 + (int)$end->format('n');
        
        $isEndOfMonth = function ($datetime) {
            $endOfMonth = clone $datetime;
            $endOfMonth = $endOfMonth->modify('last day of this month');
            return $datetime->format('d') == $endOfMonth->format('d');
        };
        
        if ($isEndOfMonth($start) && $isEndOfMonth($end)) {
            $startDayTime = (int)$start->format('dHis');
            $endDayTime = (int)($start->format('d') . $end->format('His'));
        } else {
            $startDayTime = (int)$start->format('dHis');
            $endDayTime = (int)$end->format('dHis');
        }
        
        if ($startDayTime > $endDayTime) {
            return $sign * ($endMonth - $startMonth - 1);
        }
        return $sign * ($endMonth - $startMonth);
    }
    
    /**
    *   diffWeek
    *
    *   @return int
    **/
    public function diffWeek()
    {
        return (int)($this->diffDay() / static::DAY_PER_WEEK);
    }
    
    /**
    *   diffDay
    *
    *   @return int
    **/
    public function diffDay()
    {
        return (int)$this->startDay->diff($this->endDay)->format('%r%a');
    }
    
    /**
    *   diffHour
    *
    *   @return int
    **/
    public function diffHour()
    {
        return (int)($this->diffSecond()
            / static::SECOND_PER_MINUTE
            / static::MINUTE_PER_HOUR
        );
    }
    
    /**
    *   diffMinute
    *
    *   @return int
    **/
    public function diffMinute()
    {
        return (int)($this->diffSecond() / static::SECOND_PER_MINUTE);
    }
    
    /**
    *   diffSecond
    *
    *   @return int
    **/
    public function diffSecond()
    {
        return $this->endDay->getTimestamp() - $this->startDay->getTimestamp();
    }
    
    /**
    *   getStartDay
    *
    *   @return DateTimeImmutable
    **/
    public function getStartDay()
    {
        return $this->startDay;
    }
    
    /**
    *   getEndDay
    *
    *   @return DateTimeImmutable
    **/
    public function getEndDay()
    {
        return $this->endDay;
    }
}
