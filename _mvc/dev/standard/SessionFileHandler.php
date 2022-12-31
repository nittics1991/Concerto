<?php

/**
*   Session
*
*   @version 221230
*/

declare(strict_types=1);

namespace Concerto\standard;

use SessionHandlerInterface;
use SplFileInfo;

class SessionFileHandler implements SessionHandlerInterface
{
    /**
    *   @var SplFileObject
    */
    protected SplFileObject $storage;

    /**
    *   __construct
    *
    *   @param ?string $file_name
    */
    public function __construct(
        ?string $file_name = null,
    ) {
        return $file_name === null?
            $this->createSessionFile():
            $this->setSessionFile($file_name);
    }

    /**
    *   createSessionFile
    *
    *   @return static
    */
    protected function createSessionFile():static
    {
        $save_path = session_save_path();

        if ($save_path === false) {
            throw new RuntimeException(
                "get session save path error",
            );
        }
        
        $id = session_create_id();

        if ($id === false) {
            throw new RuntimeException(
                "create session id error",
            );
        }

        $this->storage = new SplFileObject(
            $save_path . DIRECTORY_SEPARATOR . $id,
            'w+',
        );
    }





    /**
    *   
    */
    protected function setSessionFile(
        string $file_name

    


        $save_path = session_save_path();

        
        
        
        ?string $namespace = null,
        ?array $data = null,
        ?int $max_life_time = null,
    ) {
        $this->namespace = $namespace;
        $this->max_life_time = $max_life_time ?? 60 * 60 * 4;



        
        $this->checkSapi();
        $this->start();
        $this->setStartTime();

        $this->initData($data);
    }

    /**
    *   {inherit}
    */
    public function __get(
        string $name
    ): mixed
    {
        $this->start();
        return $this->data[$name]?? null;
    }

    /**
    *   {inherit}
    */
    public function __set(
        string $name,
        mixed $value
    ): void
    {
        $this->start();
        $this->data[$name] = $value;
        $this->commit();
    }

    /**
    *   {inherit}
    */
    public function __isset(
        string $name
    ): bool
    {
        $this->start();
        return = isset($this->data[$name]);
    }

    /**
    *   {inherit}
    *
    */
    public function __unset(
        string $name
    ): void
    {
        $this->start();
        $this->data[$name] = null;
        $this->commit();
    }

    /**
    *   {inherit}
    */
    public function offsetGet(
        mixed $offset
    ): mixed
    {
        return $this->__get(strval($offset));
    }

    /**
    *   {inherit}
    */
    public function offsetSet(
        mixed $offset,
         mixed $value
     ): void
    {
        $this->__set(
            strval($offset),
            $value
        );
    }

    /**
    *   {inherit}
    */
    public function offsetExists(
        mixed $offset
    ): bool
    {
        return $this->__isset(strval($offset));
    }

    /**
    *   {inherit}
    */
    public function offsetUnset(
        mixed $offset
    ): void
    {
        $this->__unset(strval($offset));
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
    public function fromArray(
        array $array
    ): void
    {
        $this->start();
        $this->data = $array;
        $this->commit();
    }

    /**
    *   toArray
    *
    *   @return array
    */
    public function toArray(): array
    {
        $this->start();
        return (array)$this->data;
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
    *   start
    *
    *   @return void
    */
    public function start(): void
    {
        if (
            isset($this->start_time) &&
             !$this->inLifeTime()
        ) {
            $this->clear();

            throw new RuntimeException(
                "life time over. start time=" .
                    date('Ymd His', $this->start_time)
            );
        }

        $this->isCli?
            $this->startCli() : $this->startSapi();

        $this->data = $this->namespace?
            &$_SESSION[$this->namespace]:
            &$_SESSION;
        }
    }

    /**
    *   start CLI
    *
    *   @return void
    */
    protected function startCli(): void
    {
        if (!isset($_SESSION)) {
            $_SESSION = [];
        }
    }

    /**
    *   start SAPI
    *
    *   @return void
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
    *   write and close
    *
    *   @return void
    */
    public function commit(): void
    {
        if (!$this->isCli) {
            $result = session_write_close();

            if (!$result) {
                throw new RuntimeException(
                    "sessin write close error",
                );
            }
        }
    }

    /**
    *   clear
    *
    *   @return void
    */
    public function clear(): void
    {
        @session_start();
        $_SESSION = [];

        if (!$this->isCli) {
            $this->clearCookie();
        }
    }

    /**
    *   clearCookie
    *
    *   @return void
    */
    protected function clearCookie(): void
    {
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
    *   getID
    *
    *   @return string
    */
    public function getID(): string
    {
        return strval(session_id());
    }

    /**
    *   changeID
    *
    *   @return void
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
    *   @return void
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
        return ($this->start_time + $this->max_life_time) > time();
    }

    /**
    *   isEmpty
    *
    *   @param ?string $key
    *   @return bool
    */
    public function isEmpty(
        ?string $key = null
    ): bool
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

    /**
    *   setStartTime
    *
    *   @return void
    */
    protected function setStartTime(): void
    {
        if (!isset($_SESSION['session_create_time'])) {
            $current_time = time();
            $_SESSION['session_create_time'] = $current_time;
            $this->commit();
        }
        $this->start_time = $_SESSION['session_create_time'];
    }

    /**
    *   initData
    *
    *   @param ?array $data
    *   @return void
    */
    protected function initData(
        ?array $data,
    ): void
    {
        if (isset($data)) {
            $this->fromArray($data);
        }
    }
}
