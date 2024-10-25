<?php

/**
*   Memento Cookie Manager
*
*   @version 221212
*/

declare(strict_types=1);

namespace Concerto\pattern;

use Concerto\pattern\{
    Memento,
    MementoCookieCaretaker,
    MementoOriginator
};
use Concerto\standard\Cookie;

class MementoCookieManager
{
    /**
    *   @var string
    */
    protected string $namespace;

    /**
    *   @var MementoOriginator
    */
    protected MementoOriginator $originator;

    /**
    *   @var Cookie
    */
    protected Cookie $cookie;

    /**
    *   @var MementoCookieCaretaker
    */
    protected MementoCookieCaretaker $caretaker;

    /**
    *   __construct
    *
    *   @param string $namespace
    *   @param mixed[] $config
    *   @param ?MementoOriginator $originator
    */
    public function __construct(
        string $namespace,
        array $config,
        ?MementoOriginator $originator = null
    ) {
        $this->namespace = $namespace;

        $this->originator = $originator ??
            new MementoOriginator();

        $this->cookie = new Cookie($config);

        $this->caretaker =
            new MementoCookieCaretaker($this->cookie);
    }

    /**
    *   入庫
    *
    *   @return static
    */
    public function setStorage(): static
    {
        $this->caretaker->setStorage(
            $this->namespace,
            $this->originator->createMemento()
        );

        return $this;
    }

    /**
    *   出庫
    *
    *   @return mixed
    */
    public function getStorage(): mixed
    {
        $memento = $this->caretaker
            ->getStorage($this->namespace);

        if (
            is_object($memento) &&
            $memento instanceof Memento
        ) {
            $this->originator->setMemento($memento);

            if ($this->originator->isValid()) {
                return $this->originator->getOriginator();
            }
        }

        return null;
    }

    /**
    *   削除
    *
    *   @return static
    */
    public function removeStorage(): static
    {
        $namespace = $this->namespace;

        if (isset($this->cookie->$namespace)) {
            unset($this->cookie->$namespace);
        }

        return $this;
    }
}
