<?php

namespace Concerto\test\delegator;

use DateInterval;

interface MyInterface
{
    
    //delegate library
    
    //argument not LIBRARY return not LIBRARY
    public function format(string $format): string;

    //argument LIBRARY return not LIBRARY
    public function diff(MyInterface $targetObject, bool $absolute = false): DateInterval;

    //argument not LIBRARY return LIBRARY
    public function modify(string $modifier): ?MyInterface;
    
    //onlyMyFunction
    
    //argument not LIBRARY return not LIBRARY
    public function onlyMyNonArgNonRet(string $str): string;
    
    //argument LIBRARY return not LIBRARY
    public function onlyMyArgNonRet(MyInterface $obj): string;
    
    //argument not LIBRARY return LIBRARY
    public function onlyMyNonArgRet(string $str): MyInterface;
    
    //argument LIBRARY return LIBRARY
    public function onlyMyArgRet(MyInterface $obj): MyInterface;
} 
