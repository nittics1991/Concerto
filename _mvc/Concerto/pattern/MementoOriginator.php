<?php

/**
*   Originator
*
*   @version 230117
*/

declare(strict_types=1);

namespace Concerto\pattern;

use Concerto\pattern\Memento;

class MementoOriginator extends Memento
{
    /**
    *   @var mixed
    */
    protected mixed $local_storage;

    /**
    *   __construct
    *
    *   @param mixed $data
    */
    public function __construct(
        mixed $data = null
    ) {
        $this->local_storage = $data;
    }

    /**
    *   Memento生成
    *
    *   @return Memento
    */
    public function createMemento(): Memento
    {
        return new Memento($this->local_storage);
    }

    /**
    *   Memento復元
    *
    *   @param Memento $memento
    */
    public function setMemento(
        Memento $memento
    ): void {
        $this->local_storage = $memento->getMemento();
    }

    /**
    *   データ取得
    *
    *   @return mixed
    */
    public function getOriginator(): mixed
    {
        return $this->local_storage;
    }

    /**
    *   データ設定
    *
    *   @param mixed $val
    *   @return static
    */
    public function setOriginator(
        mixed $val
    ): static {
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
