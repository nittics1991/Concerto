<?php

/**
*   Originator
*
*   @version 210614
*/

declare(strict_types=1);

namespace Concerto\pattern;

use Concerto\pattern\Memento;

class MementoOriginator extends Memento
{
    /**
    *   データ
    *
    *   @var mixed
    */
    protected $local_storage;

    /**
    *   __construct
    *
    *   @param mixed $params データ
    */
    public function __construct($params = null)
    {
        $this->local_storage = $params;
    }

    /**
    *   Memento生成
    *
    *   @return object
    */
    public function createMemento()
    {
        return new Memento($this->local_storage);
    }

    /**
    *   Memento復元
    *
    *   @param Memento $memento
    */
    public function setMemento(Memento $memento): void
    {
        $this->local_storage = $memento->getMemento();
    }

    /**
    *   データ取得
    *
    *   @return mixed
    */
    public function getOriginator()
    {
        return $this->local_storage;
    }

    /**
    *   データ設定
    *
    *   @param mixed $val
    *   @return object $this
    */
    public function setOriginator($val)
    {
        $this->local_storage = $val;
        return $this;
    }

    /**
    *   isValid
    *
    *   @return bool
    */
    public function isValid(): bool
    {
        return true;
    }
}
