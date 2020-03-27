<?php

/**
*   BusinessDateTime
*
*   @version 171106
*/

namespace Concerto\datetime;

use Carbon;
use Carbon\Exceptions\InvalidDateException;

class BusinessDateTime extends Carbon
{
    const DEFAULT_START_FISCAL_MONTH = 4;
    
    /**
    *   first month of fiscal year
    *
    *   @var int
    **/
    protected static $startFiscalMonth = self::DEFAULT_START_FISCAL_MONTH;
    
    /**
    *   month of fiscal year
    *
    *   @var array
    **/
    protected static $fiscalMonths = [
        4, 5, 6, 7, 8, 9, 10, 11, 12, 1, 2, 3
    ];
    
    /**
    *   quarterFormat
    *
    *   @var string
    *       Q:1-4
    **/
    protected static $quarterFormat = 'Y\QQ';    //yyyyQn
    
    /**
    *   halfFormat
    *
    *   @var string
    *       f:1-2 K:K|S
    **/
    protected static $halfFormat = 'Y\Hf';    //yyyyHn
    
    
    
    
    
    /**
    *   setStartFiscalMonth
    *
    *   @param int
    *   @throws InvalidArgumentException
    **/
    public function setStartFiscalMonth($month)
    {
        if (!is_int($month) || $month < 1 || $month > 12) {
            throw new InvalidArgumentException('does not exist month:{$month}');
        }
        static::$startFiscalMonth = $month;
        
        $this->fiscalMonths = [];
        for ($i = 0; $i < 12; $i++) {
            $month = ($month > 12) ? $month - 12 + 1 : $month;
            $this->fiscalMonths[] = $month;
            $month++;
        }
    }
    
    /**
    *   createFromQuarter
    *
    *   @param int|null
    *   @param int|null
    *   @param DateTimeZone|string|null
    *   @return this
    **/
    public static function createFromQuarter(
        $year = null,
        $quarter = null,
        $ts = null
    ) {
    }
    
    /**
    *   createFromHalf
    *
    *   @param int|null
    *   @param int|null
    *   @param DateTimeZone|string|null
    *   @return this
    **/
    public static function createFromHalf(
        $year = null,
        $quarter = null,
        $ts = null
    ) {
    }
    
    /**
    *   {inherit}
    *
    **/
    public function __get($name)
    {
        if ($name != 'quarter') {
        }
        
        
        if ($name != 'half') {
            return parent::__get($name);
        }
    }
    
    /**
    *   setToQuarterStringFormat
    *
    *   @param string
    **/
    public function setToQuarterStringFormat($format)
    {
        static::$quarterFormat = $format;
    }
    
    /**
    *   setToHalfStringFormat
    *
    *   @param string
    **/
    public function setToHalfStringFormat($format)
    {
        static::$halfFormat = $format;
    }
    
    /**
    *   toQuarterString
    *
    *   @param string
    *   @return string
    *   @throws InvalidArgumentException
    **/
    public function toQuarterString()
    {
        $backup = static::$toStringFormat;
        
        
        $formated = $this->format
    }
    
    /**
    *   toHalfString
    *
    *   @param string
    *   @return string
    *   @throws InvalidArgumentException
    **/
    public function toHalfString()
    {
        //2017F1
        setToHalfStringFormat('Yf')
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /**
    *   nthOfQuarter
    *
    *   @param int
    *   @param int
    *   @return this
    **/
    public function nthOfQuarter($nth = 1, $dayOfWeek = null)
    {
        if ($nth < 1 || $nth > 4) {
            return false;
        }
        
        $pos = static::MONTHS_PER_QUARTER * $nth - 2;
        $month = static $fiscalMonths[$pos];
        $year = ($month < static::$startFiscalMonth) ?
            $this->year - 1 : $this->year;
        return $this->setDate($year, $month, 1, 1)->
    }
    
    /**
    *   firstQuarter
    *
    *   @param int
    *   @return this
    **/
    public function firstQuarter($dayOfWeek = null)
    {
        return $this->nthOfQuarter(1, $dayOfWeek);
    }
    
    /**
    *   lastQuarter
    *
    *   @param int
    *   @return this
    **/
    public function lastQuarter($dayOfWeek = null)
    {
        return $this->nthOfQuarter(4, $dayOfWeek);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    /**
    *   isFirstHalf
    *
    *   @param DateTimeInterface|int|null
    *   @return bool
    **/
    public function isFirstHalf()
    {
        return $this->month >= 4 &&  $this->month <= 9
    }
    
    /**
    *   isLastHalf
    *
    *   @param DateTimeInterface|int|null
    *   @return bool
    **/
    public function isFirstHalf()
    {
        return $this->month >= 4 &&  $this->month <= 9
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /**
    *   startOfFiscalYear
    *
    *   @return string
    **/
    public function startOfFiscalYear($ts = null)
    {
        $year = ($this - )
        
        return $this->setDateTime()
    }
    
    
    
    
    
    /**
    *   thisFiscalYear
    *
    *   @return string
    **/
    public static function thisFiscalYear($ts = null)
    {
        return
    }
}
