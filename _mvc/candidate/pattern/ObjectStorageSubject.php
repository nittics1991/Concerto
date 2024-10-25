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
    protected SplObjectStorage $storage;

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
    *   @inheritDoc
    *
    */
    public function attach(SplObserver $observer):void
    {
        $this->storage->attach($observer);
    }

    /**
    *   @inheritDoc
    *
    */
    public function detach(SplObserver $observer):void
    {
        $this->storage->detach($observer);
    }

    /**
    *   @inheritDoc
    *
    */
    public function notify():void
    {
        foreach ($this->storage as $observer) {
            $observer->update($this);
        }
    }
}
