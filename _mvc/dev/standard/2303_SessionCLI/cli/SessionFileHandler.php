<?php

/**
*   Session
*
*   @version 230101
*/

declare(strict_types=1);

namespace Concerto\standard;

use CallbackFilterIterator;
use DateTimeImmutable;
use FilesystemIterator;
use RuntimeException;
use SessionHandlerInterface;

class SessionFileHandler implements SessionHandlerInterface
{
    /**
    *   @var string
    */
    protected string $path;

    /**
    *   @var string
    */
    protected string $id;

    /**
    *   @inheritDoc
    */
    public function __destruct()
    {
        if (
            isset($this->path) &&
            isset($this->id)
        ) {
            $this->write(
                $this->id,
                serialize($_SESSION),
            );
        }
    }

    /**
    *   @inheritDoc
    */
    public function open(
        string $path,
        string $name
    ): bool
    {
        $this->path = $path;
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
        string $id
    ): string|false
    {
        $this->id = $id;

        $file_path = $this->path . DIRECTORY_SEPARATOR . $id;

        if (!file_exists($file_path)) {
            return "";
        }

        $encoded_contents = file_get_contents(
            $file_path,
        );

        if ($encoded_contents === false) {
            return "";
        }
        return unserialize($encoded_contents);
    }

    /**
    *   @inheritDoc
    */
    public function write(
        string $id,
        string $data
    ): bool
    {
        $this->id = $id;

        $length = file_put_contents(
            $this->path . DIRECTORY_SEPARATOR . $id,
            serialize($data),
            LOCK_EX,
        );

        if ($length === false) {
            throw new RuntimeException(
                "write error id={$id}",
            );
        }
        
        return true;
    }
    
    /**
    *   @inheritDoc
    */
    public function destroy(
        string $id
    ): bool
    {
        $_SESSION = [];
        $this->write($id, '');
        return true;
    }
    
    /**
    *   @inheritDoc
    */
    public function gc(
        int $max_lifetime
    ): int|false
    {
        $timestamp = (new DateTimeImmutable($max_lifetime . ' sec'))
            ->getTimestamp();

        $iterator = new CallbackFilterIterator(
            new FilesystemIterator(
                $this->path,
                FilesystemIterator::KEY_AS_PATHNAME |
                    FilesystemIterator::CURRENT_AS_FILEINFO |
                    FilesystemIterator::SKIP_DOTS
            ),
            function ($fileInfo, $path, $iterator) use ($timestamp) {
                return $fileinfo->getMTime() < $timestamp;
            }
        );

        $count = 0;

        foreach ($iterator as $path => $fileInfo) {
            $result = unlink($fileInfo->getFileName());

            if ($result === false) {
                throw new RuntimeException(
                    "file delete error:" .
                        $fileInfo->getFileName(),
                );
            }

            $count++;
        }
        
        return $count;
    }
}
