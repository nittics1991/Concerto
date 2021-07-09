<?php

/**
*   Memento Cookie Manager
*
*   @version 210614
*/

declare(strict_types=1);

namespace Concerto\pattern;

use Exception;
use RuntimeException;
use Concerto\pattern\{
    Memento,
    MementoCookieCaretaker,
    MementoOriginator
};
use Concerto\standard\Cookie;

class MementoCookieManager
{
    /**
    *   名前空間
    *
    *   @var string
    */
    protected $namespace;

    /**
    *   originator
    *
    *   @var MementoOriginator
    */
    protected $originator;

    /**
    *   cookie
    *
    *   @var Cookie
    */
    protected $cookie;

    /**
    *   caretaker
    *
    *   @var MementoCookieCaretaker
    */
    protected $caretaker;

    /**
    *   __construct
    *
    *   @param string $namespace 名前空間
    *   @param array $config
    *   @param ?MementoOriginator $originator
    */
    public function __construct($namespace, array $config, $originator = null)
    {
        $this->namespace = $namespace;
        $this->originator = isset($originator) ?
            $originator : new MementoOriginator();

        try {
            $this->cookie = new Cookie($config);
            $this->caretaker = new MementoCookieCaretaker($this->cookie);
        } catch (Exception $e) {
            throw new RuntimeException("caretaker create error", 0, $e);
        }
    }

    /**
    *   入庫
    *
    *   @return object $this
    */
    public function setStorage()
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
    *   @return mixed データ
    */
    public function getStorage()
    {
        $memento = $this->caretaker->getStorage($this->namespace);

        if (is_object($memento) && ($memento instanceof Memento)) {
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
    *   @return object $this
    */
    public function removeStorage()
    {
        $namespace = $this->namespace;

        if (isset($this->cookie->$namespace)) {
            unset($this->cookie->$namespace);
        }
        return $this;
    }
}
