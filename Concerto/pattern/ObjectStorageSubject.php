<?php

/**
*   ObjectStorage SplSubject
*
*   @version 151209
**/

declare(strict_types=1);

namespace Concerto\pattern;

use SplObjectStorage;
use SplObserver;
use SplSubject;

class ObjectStorageSubject implements SplSubject
{
    /**
    *   observers
    *
    *   @var SplObjectStorage
    */
    protected $storage;
    
    /**
    *   __construct
    *
    */
    public function __construct()
    {
        $this->storage = new SplObjectStorage();
    }
    
    /**
    *   一括出力
    *
    *   @return array SplObserver
    */
    public function toArray()
    {
        $result = array();
        
        foreach ($this->storage as $obj) {
            $result[] = $obj;
        }
        return $result;
    }
    
    /**
    *   一括入力
    *
    *   @param array $array SplObserver
    */
    public function fromArray(array $array)
    {
        foreach ($array as $obj) {
            $this->attach($obj);
        }
    }
    
    /**
    *   {inherit}
    *
    */
    public function attach(SplObserver $observer)
    {
        $this->storage->attach($observer);
    }
    
    /**
    *   {inherit}
    *
    */
    public function detach(SplObserver $observer)
    {
        $this->storage->detach($observer);
    }
    
    /**
    *   {inherit}
    *
    */
    public function notify()
    {
        $result = array();
        foreach ($this->storage as $observer) {
            $result[] = $observer->update($this);
        }
        return $result;
    }
}
