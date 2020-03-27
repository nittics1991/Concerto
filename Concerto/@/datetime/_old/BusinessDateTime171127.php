<?php

class BusinessDateTime extends Carbon
{
    const MONTHS_PER_HALF = 6;
    
    /**
    *   会計年度開始月
    *
    **/
    protected static $startMonthOfBusiness = 4;
    
    /**
    *   会計年度開始月設定
    *
    *   @param int 年
    *   @return static
    */
    public function setStartMonthOfBusiness(int $month)
    {
        if (($month >= 1) && ($month <= 12)) {
            static::$startMonthOfBusiness = $month;
        }
        return $this;
    }
    
    /**
    *   {inherit}
    *
    */
    public function __get($name)
    {
        if ($name === 'quarter') {
            $month = $this->month - static::$startMonthOfBusiness + 1;
            
            if ($this->month < static::$startMonthOfBusiness) {
                $month += 12;
            }
            return (int)ceil($month / static::MONTHS_PER_QUARTER);
        }
        
        if ($name === 'half') {
            $month = $this->month - static::$startMonthOfBusiness + 1;
            
            if ($this->month < static::$startMonthOfBusiness) {
                $month += 12;
            }
            return (int)ceil($month / static::MONTHS_PER_HALF);
        }
        
        return parent::__get($name);
    }
    
    /**
    *   半期加算
    *
    *   @param int
    *   @return static
    **/
    public function addHalfs($value)
    {
        return $this->addMonths(static::MONTHS_PER_HALF * $value);
    }
    
    /**
    *   半期加算
    *
    *   @param int
    *   @return static
    **/
    public function addHalf($value = 1)
    {
        return $this->addHalfs($value);
    }
    
    /**
    *   半期減算
    *
    *   @param int
    *   @return static
    **/
    public function subHalfs($value)
    {
        return $this->addQuarters(-1 * $value);
    }
    
    /**
    *   半期減算
    *
    *   @param int
    *   @return static
    **/
    public function subHalf($value = 1)
    {
        return $this->subHalfs($value);
    }
    
    /**
    *   {inherit}
    *
    */
    public function startOfQuarter()
    {
        $year = $this->year;
        $month = $this->month;
        
        if ($this->month < static::$startMonthOfBusiness) {
            $year++;
            
            $month = ($month - static::$startMonthOfBusiness) + 12;
            $mod = $month % static::MONTHS_PER_QUARTER;
            $month = $this->month - $mod;
            
            if ($month <= 0) {
                $month += 12;
            }
        } else {
            $month = ($month - static::$startMonthOfBusiness);
            $mod = $month % static::MONTHS_PER_QUARTER;
            $month = $this->month - $mod;
        }
        
        return $this->setDateTime($year, $month, 1, 0, 0, 0);
    }
    
    
    /**
    *   {inherit}
    *
    */
    public function firstOfQuarter($dayOfWeek = null)
    {
        return $this->startOfQuarter()->firstOfMonth($dayOfWeek);
    }
    
    /**
    *   {inherit}
    *
    */
    public function lastOfQuarter($dayOfWeek = null)
    {
        return $this->lastOfQuarter()->lastOfMonth($dayOfWeek);
    }
    
    /**
    *   {inherit}
    *
    */
    public function nthOfQuarter($nth, $dayOfWeek)
    {
        $this->addQuarter($nth)->modify(static::$days[$dayOfWeek]);
    }
    
    /**
    *   startOfHalf
    *
    *   @return static
    **/
    public function startOfHalf()
    {
        $year = $this->year;
        $month = $this->month;
        
        if ($this->month < static::$startMonthOfBusiness) {
            $year++;
            
            $month = ($month - static::$startMonthOfBusiness) + 12;
            $mod = $month % static::MONTHS_PER_HALF;
            $month = $this->month - $mod;
            
            if ($month <= 0) {
                $month += 12;
            }
        } else {
            $month = ($month - static::$startMonthOfBusiness);
            $mod = $month % static::MONTHS_PER_HALF;
            $month = $this->month - $mod;
        }
        
        return $this->setDateTime($year, $month, 1, 0, 0, 0);
    }
    
    /**
    *   endOfQuarter
    *
    *   @return static
    **/
    public function endOfQuarter()
    {
        return $this->startOfHalf()
            ->addMonths(static::MONTHS_PER_HALF - 1)
            ->endOfMonth();
    }
    
    /**
    *   firstOfHalf
    *
    *   @return static
    **/
    public function firstOfHalf($dayOfWeek = null)
    {
        return $this->startOfHalf()->firstOfMonth($dayOfWeek);
    }
    
    /**
    *   lastOfHalf
    *
    *   @return static
    **/
    public function lastOfHalf($dayOfWeek = null)
    {
        return $this->lastOfHalf()->lastOfMonth($dayOfWeek);
    }
    
    /**
    *   nthOfHalf
    *
    *   @param int
    *   @param string
    *   @return static
    **/
    public function nthOfHalf($nth, $dayOfWeek)
    {
        $this->addQHalf($nth)->modify(static::$days[$dayOfWeek]);
    }
}
