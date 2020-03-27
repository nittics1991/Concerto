<?php

createFromDateTime($datetime)でobj生成
createFromString($date)で設定した日付文字列からobj生成
    createFromFormat　のショートカット
    普段生成するときはこれにしたい
setCreateFromFormat($format)で日付文字列初期値変更 ?
    ==  > instanceでないと出来ない

DateTimeInterface $date
    = BusinessDateFactory::createFromDateTime(DateTimeInterface $datetime);

$yyyymm = BusinessDateFactory::createFromString(string $date);  $date = 'yyyymm'

BusinessDateFactory::setCreateFromFormat(string $format) : BusinessDateFactory;

==  > createFromFormat　のformatをconfigから取得
==  > configより簡単に取得できる方法が無いか ?

///////////////////////////////////////////////

DateTime,Carbon 等をwrapしてYyyymmObjectやFiscalYearを作りたい
Period生成 = iteratorを持つか？
    ==  > ルールが複雑なので外でfactort - builder化


class BusinessDate implements IteratorAggrigate
{
    //mutable? immutable?
    private DateTimeInterface $datetime;    //DateTime,Carbon等
    
    //Carbon等のmethod call ==>delegate
    public function __call($name, $arguments)
    {
        if (method_exists($this->datetime)) {
            //return objは自classを返す
            return $result instanceof DateTimeInterface ?
                static::createFromDateTime($result) :
                $result;
        }
        throw new BatMethodCallException();
    }
    
    ///////////////////////////////////////////////////
    
    private const $format = 'Ymd His';  //YyyymmObjectなどconstructで使う
    
    public function __construct($time, $timezone)
    {
        //どうやる?
        $
    }
    
    
    public static function createFromDateTime(DateTimeInterface $datetime)
    {
        $dateString = $datetime->format(),
        
        return new static(
            $dateString,
            $datetime->getTimeZone()
        );
    }
    
    //////////////////////////////////////////////////////////
    
    private $toStringFormat = 'Ymd His';
    
    public function toString()
    {
        return $datetime->format($this->toStringFormat);
    }
    
    
    
    //////////////////////////////////////////////////////////
    
    //Period の動作を持ちたい==> Period はtraversableだが...
    //getPeriod()とするか?
    public function getIterator()
    {
        return $this->iterator;
    }
    
    //外部からPeriodループを変更したい
    public function setIterator(Iterator $iterator)
    {
        $this->iterator = $iterator;
        return $this;
    }
    
        //FiscalYearのyyyymm[0-5]を作るiterator?
    class FiscalYearIterator implements
    {
            
    }


    ////
    
    public function getPeriod()
    {
        return $this->period;
    }
    
    public function setPeriod(DatePeriod あるいは　DateInterval どうする)
    {
        $this->period = $period;
        return $this;
    }
    
    ////
    
    public function setInterval(DateInterval $interval, ? $option = null)
    {
        $this->interval = $interval;
        $this->periodOption = $option;
    }
    
    public function getPeriod()
    {
        return new DatePeriod(
            $this->datetime,
            //FiscalYearならP6M
            //DateならPT1H MonthならP1D
            $this->interval,
            //FiscalYearなら6ヶ月後　YYYYMMなら月末 Datetimeなら？
            //Dateなら23:59:59 Quoteなら2ヶ月後 Hourなら59
            $this->datetime->modify()
            $this->periodOption //DatePeriod::EXCLUDE_START_DATE
        );
    }
    
    ==  > Period生成は複雑なので外に出す == > factory ? builder ?
    //peridのconstruct自体がbuilderっぽいけど
    foreach ($factory->createDatePeriod($fiscalYear) as $datetime) {
    }
}

///////////////////////////////////////////////
Carbonをextends ? 依存したくない
DateTimeを生成するfactoryとするか ?

ValueObjectとしてはYyyymmObjectだが...
日付obj(entityAggregateのproperty)のビジネスロジックmethodって何がある？
SpecialSalseDay::is() : bool　単体ではbool系ぐらいでは
日付の集合Class / collectionなら色々methodがあるかも
        
WFの計画日実績日のOBJってどうなる ?
WfPlanAndPerormanceDateClass ?
    getPlanDate() : DateTimeInterface
    registerdPerformanceDate() : bool
    isPastPlanDate() : bool
    remainingTime() : DateInterval
    
DDDドメイン独自の型は難しい
一般的な型でclass,propery名をドメイン名で命名ならできるけど
でもfiscalYear->next()とか欲しい
あるいはdatetime->nextFiscalYear()か ?
    carbonはnextQuoter()ある
    やっぱりdelegate ?


class FiscalYear
{
    public function __construct($time, $timezone)
    {
        $this->datetime = (new DateTime($time, $timezone))
            ->modify('');
        
        //toString用
        $this->setToStringFormater();
    }
    
    public function __call($name, $arguments)
    {
        if (method_exists($this->datetime)) {
            $result = call_user_func_array(
                [$this->datetime, $name],
                $arguments
            );
            
            //return objは自classを返す
            return $result instanceof DateTimeInterface ?
                new static(
                    $result->format('c'),   //ISO 8601
                    $result->getTimeZone()
                ) :
                $result;
        }
        throw new BatMethodCallException();
    }
    
    
    public function toString()
    {
        return call_user_func(
            callable $this->toStringFormater,
            $this->datetime
        );
    }
    
    //fn($datetime)
    public function setToStringFormater(?calbable $callback = null)
    {
        if (is_callable($callback)) {
            $this->toStringFormater = $callback;
            return $this;
        }
        
        $this->toStringFormater = function ($datetime) {
            $year = $datetime->format('y');
            $month = $datetime->format('m');
            
            if ($month >= '04' && $month >= '09') {
                return "{$year}K";
            }
            
            if ($month >= '10' && $month >= '12') {
                return "{$year}S";
            }
            
            return (string)((int)$year - 1) . 'S';
        };
        
        return $this;
    }
}
