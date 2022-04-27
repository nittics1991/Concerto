<?php

/**
*   ObjectStorage SplSubject
*
*   @version 210614
*/

declare(strict_types=1);

namespace candidate\pattern;

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
    *   @return array
    */
    public function toArray()
    {
        $result = [];

        foreach ($this->storage as $obj) {
            $result[] = $obj;
        }
        return $result;
    }

    /**
    *   一括入力
    *
    *   @param SplObserver[] $array
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
        $result = [];
        foreach ($this->storage as $observer) {
            $result[] = $observer->update($this);
        }
        return $result;
    }
}
