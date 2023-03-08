<?php

/**
*   Session
*
*   @version 230309
*/

declare(strict_types=1);

namespace Concerto\standard;

use SessionHandlerInterface;

class FileSessionHandler implements SessionHandlerInterface
{
    /**
    *   @var string
    */
    protected string $savePath;

    /**
    *   @var string
    */
    protected string $id;

    /**
    *   @inheritDoc
    */
    public function open(
        string $savePath,
        string $name,
    ): bool
    {
        $this->savePath = $savePath;

        if (!is_dir($this->savePath)) {
            mkdir($this->savePath, 0777);
        }

        return true;
    }

    /**
    *   @inheritDoc
    */
    public function close(): bool
    {
        return true;
    }

    /**
    *   @inheritDoc
    */
    public function read(
        string $id,
    ): string|false
    {
        $this->data = false;

        $filename = $this->savePath.'/sess_'.$id;

        if (file_exists($filename)){
            $this->data = @file_get_contents($filename);
        }

        if ($this->data === false){
            $this->data = '';
        }

        return $this->data;
    }

    /**
    *   @inheritDoc
    */
    public function write(
        string $id,
        string $data,
    ): bool
    {
        $filename = $this->savePath.'/sess_'.$id;

        // check if data has changed since first read
        if ($data !== $this->data) {
            // write data
            return @file_put_contents($filename, $data, LOCK_EX) === false ?
                false : true;
        }else {
            // let's not forget to postpone session garbage collection
            return @touch($filename);
        }
    }

    /**
    *   @inheritDoc
    */
    public function destroy(
        string $id,
    ): bool
    {
        $filename = $this->savePath.'/sess_'.$id;

        if (file_exists($filename)) {
            @unlink($filename);
        }

        return true;
    }

    /**
    *   @inheritDoc
    */
    public function gc(
        int $maxlifetime,
    ): int|false
    {
        // garbage collection, delete obsolete session files
        foreach (glob($this->savePath.'/sess_*') as $filename) {
            if (
                filemtime($filename) + $maxlifetime < time() &&
                file_exists($filename) 
            ) {
                @unlink($filename);
            }
        }

        return true;
    }
}

