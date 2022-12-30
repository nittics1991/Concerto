<?php

/**
*   Session
*
*   @version 210826
*/

declare(strict_types=1);

namespace Concerto\standard;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use RuntimeException;
use Traversable;

class Session implements ArrayAccess, IteratorAggregate, Countable
{
    /**
    *   @var string[]
    */
    protected const SAPI_CLIS = ['cli', 'phpdbg'];

    /**
    *   @var bool
    */
    protected bool $isCli;

    /**
    *   @var ?mixed[]
    */
    protected ?array $data;

    /**
    *   @var ?string
    */
    protected ?string $namespace;

    /**
    *   @var ?int
    */
    protected ?int $max_life_time;

    /**
    *   @var int
    */
    protected int $start_time;

    /**
    *   __construct
    *
    *   @param ?string $namespace SESSION空間名
    *   @param ?array $data セッションデータ
    *   @param ?int $max_life_time 最大ライフタイム
    */
    public function __construct(
        ?string $namespace = null,
        ?array $data = null,
        ?int $max_life_time = null,
    ) {
        in_array(php_sapi_name(), static::SAPI_CLIS) ?
        $this->namespace = $namespace;
        $this->max_life_time = $max_life_time ?? 60 * 60 * 4;
        $this->checkSapi();
        $this->initData($data);
    }

    /**
    *   {inherit}
    */
    public function __get(string $name): mixed
    {
        $this->start();
        $result = (isset($this->data[$name])) ?
            $this->data[$name] : null;
        return $result;
    }

    /**
    *   {inherit}
    */
    public function __set(string $name, mixed $value): void
    {
        $this->start();
        $this->data[$name] = $value;
        $this->commit();
    }

    /**
    *   {inherit}
    */
    public function __isset(string $name): bool
    {
        $this->start();
        $result = isset($this->data[$name]);
        return $result;
    }

    /**
    *   {inherit}
    *
    */
    public function __unset(string $name): void
    {
        $this->start();
        $this->data[$name] = null;
        $this->commit();
    }

    /**
    *   {inherit}
    */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->__get($offset);
    }

    /**
    *   {inherit}
    */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->__set($offset, $value);
    }

    /**
    *   {inherit}
    */
    public function offsetExists(mixed $offset): bool
    {
        return $this->__isset($offset);
    }

    /**
    *   {inherit}
    */
    public function offsetUnset(mixed $offset): void
    {
        $this->__unset($offset);
    }

    /**
    *   {inherit}
    */
    public function getIterator(): Traversable
    {
        $this->start();
        $result = new ArrayIterator((array)$this->data);
        return $result;
    }

    /**
    *   {inherit}
    */
    public function count(): int
    {
        $this->start();
        $result = count((array)$this->data);
        return $result;
    }

    /**
    *   {inherit}
    */
    public function unsetAll(): void
    {
        $this->start();
        $this->data = [];
        $this->commit();
    }

    /**
    *   fromArray
    *
    *   @param array $array
    */
    public function fromArray(array $array): void
    {
        $this->start();
        $this->data = $array;
        $this->commit();
        return;
    }

    /**
    *   toArray
    *
    *   @return array
    */
    public function toArray(): array
    {
        $this->start();
        $result = (array)$this->data;
        return $result;
    }

    /**
    *   checkSapi
    *
    *   @return void
    */
    protected function checkSapi(): void
    {
        $this->isCli = in_array(
            strval(php_sapi_name()),
            static::SAPI_CLIS,
        );
    }

    /**
    *   initData
    *
    *   @param ?array $data
    */
    protected function initData(?array $data): void
    {
        if (isset($data)) {
            $this->fromArray($data);
        }

        $this->start();

        if (!isset($_SESSION['session_create_time'])) {
            $current_time = time();
            $_SESSION['session_create_time'] = $current_time;
            $this->commit();
        }
        $this->start_time = $_SESSION['session_create_time'];
    }

    /**
    *   start
    *
    */
    public function start(): void
    {
        if (isset($this->start_time) && !$this->inLifeTime()) {
            $this->clear();

            throw new RuntimeException(
                "life time over. start time=" .
                    date('Ymd His', $this->start_time)
            );
        }

        $this->isCli?
            $this->startCli() : $this->startSapi();

        if (isset($this->namespace)) {
            $this->data = &$_SESSION[$this->namespace];
        } else {
            $this->data = &$_SESSION;
        }
    }

    /**
    *   start SAPI
    *
    */
    protected function startSapi(): void
    {
        $result = true;
        if (session_status() !== PHP_SESSION_ACTIVE) {
            $result = session_start();
        }

        if (!$result) {
            throw new RuntimeException(
                "failure session start"
            );
        }
    }

    /**
    *   start CLI
    *
    */
    protected function startCli(): void
    {
        if (!isset($_SESSION)) {
            $_SESSION = [];
        }
    }

    /**
    *   write and close
    *
    */
    public function commit(): void
    {
        session_write_close();
    }

    /**
    *   破棄
    *
    */
    public function clear(): void
    {
        @session_start();
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = @session_get_cookie_params();

            @setcookie(
                @(string)session_name(),
                '',
                time() - 42000,
                $params["path"] ?? null,
                $params["domain"] ?? null,
                $params["secure"] ?? null,
                $params["httponly"] ?? null,
            );
        }
        @session_destroy();
    }

    /**
    *   ID変更
    *
    */
    public function changeID(): void
    {
        $this->start();
        session_regenerate_id(true);
        $this->commit();
    }

    /**
    *   ガーベージコレクション
    *
    */
    public function gc(): void
    {
        @session_start();
        @session_gc();
        @session_destroy();
    }

    /**
    *   ライフタイム内か
    *
    *   @return bool
    */
    public function inLifeTime(): bool
    {
        return $this->start_time + $this->max_life_time > time();
    }

    /**
    *   isEmpty
    *
    *   @param ?string $key
    *   @return bool
    */
    public function isEmpty(?string $key = null): bool
    {
        if (empty($this->data)) {
            return  true;
        }

        if (!is_null($key)) {
            return isset($this->data[$key]) ?
                empty($this->data[$key]) : true;
        }

        foreach ((array)$this->data as $val) {
            if (!empty($val)) {
                return false;
            }
        }
        return true;
    }

    /**
    *   isNull
    *
    *   @param ?string $key
    *   @return bool
    */
    public function isNull(?string $key = null): bool
    {
        if ($this->data === null) {
            return  true;
        }

        if (!is_null($key)) {
            return !isset($this->data[$key]);
        }

        foreach ((array)$this->data as $val) {
            if (!is_null($val)) {
                return false;
            }
        }
        return true;
    }
}
