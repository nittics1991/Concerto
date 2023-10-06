<?php

namespace Concerto\test\delegator;

use DateTimeImmutable;
use DateTimeZone;
use LogicExceptipon;
use Concerto\delegator\StandardDelegatorObject;

use Concerto\test\delegator\{
    //LibClass,
    MyInterface
};

class MyClass extends StandardDelegatorObject implements MyInterface
{
    /**
    *   {overwrite}
    */
    protected static string $delegatorNamespace = DateTimeImmutable::class;
    
    public function __construct(string $datetime = "now", ?DateTimeZone $timezone = null)
    {
        $this->delegator = static::delegatorConstruct([
            'datetime' => $datetime,
            'timezone' => $timezone
        ]);
    }
    
    
//StandardDelegatorObjectにabstract method作るか?
    /**
    *   delegatorConstruct
    *
    *   @param array $arguments
    *   @return object
    */
    protected static function delegatorConstruct(array $arguments):object
    {
        if (!isset($arguments['datetime'])) {
            throw new LogicExceptipon(
                "argument error"
            );
        }
        
        $className = static::delegatorNamespace();
        return new $className(
            $arguments['datetime'],
            $arguments['timezone']?? null
        );
    }
    
    //implements StandardDelegatorObject
    
    /**
    *   {inherit}
    */
    protected static function convertToOriginal(
        object $delegator
    ):object{
        return static::delegatorConstruct([
            'datetime' => $delegator->format(DateTimeInterface::ATOM),
            'timezone' => $delegator->getTimezone()
        ]);
    }
    
    /**
    *   {inherit}
    */
    protected static function convertToDelegator(
        object $original
    ):object{
        return new static(
            $original->format(DateTimeInterface::ATOM),
            $original->getTimezone()
        );
    }
    


    //delegate library
    
    //argument not LIBRARY return not LIBRARY
    public function format(string $format): string
    {
        return $this->format($format);
    }

    //argument LIBRARY return not LIBRARY
    public function diff(MyInterface $targetObject, bool $absolute = false): DateInterval
    {
        return $this->diff(
            static::convertToDelegator($targetObject),
            $absolute
        );
    }

    //argument not LIBRARY return LIBRARY
    public function modify(string $modifier): ?MyInterface
    {
        return static::convertToOriginal(
            $this->modify($modifier)
        );
    }
    
    
    
    //onlyMyFunction
    
    //argument not LIBRARY return not LIBRARY
    public function onlyMyNonArgNonRet(string $str): string
    {
        return "onlyMyNonArgNonRet_{$str}";
    }
    
    //argument LIBRARY return not LIBRARY
    public function onlyMyArgNonRet(MyInterface $obj): string
    {
        return "onlyMyArgNonRet_" .
            get_class($obj);
    }
    
    //argument not LIBRARY return LIBRARY
    public function onlyMyNonArgRet(string $str): MyInterface
    {
        return new static(
            '2000-12-31 00:00:00'
        );
    }
    
    //argument LIBRARY return LIBRARY
    public function onlyMyArgRet(MyInterface $obj): MyInterface
    {
        $date_string = $obj->modify('+1 day')
            ->format(DateTimeInterface::ATOM);
        
        return new static(
            $date_string,
            $obj->getTimezone()
        );
    }
}
