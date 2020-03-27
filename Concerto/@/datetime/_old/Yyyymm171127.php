<?php

//時刻と期間でクラスを分ける




class YyyyMm implements
    toStringInterface,
    IteratorAggrigate,
    DatePeriodInterface
{
    protected $startDate;
    protected $endDate;
    protected $interval;
    
    public function __construct($value, DateInterval $interval = null)
    {
    }
    
    public static function create(DateTimeInterface $date)
    {
    }
    
    public function toString()
    {
    }
    
    
    
    
    
    
    //period
    /**
    *   {inherit}
    *
    **/
    public function getStartDate()
    {
    }
    
    //period
    /**
    *   {inherit}
    *
    **/
    public function getEndDate()
    {
    }
    
    //period
    /**
    *   {inherit}
    *
    **/
    public function inPeriod(YyyyMm $date)
    {
    }
    
    //period
    /**
    *   {inherit}
    *
    **/
    public function inPeriodAt(DateTimeInterface $date)
    {
    }
    
    //period
    public function getIterator()
    {
    }
    
    //period
    public function setInterval(DateInterval $interval)
    {
    }
    
    public function next()
    {
    }
    
    
    
    //時刻
    public function previous()
    {
    }
    
    //時刻
    public function next()
    {
    }
    
    //時刻
    public function change($period = 1)
    {
    }
}
