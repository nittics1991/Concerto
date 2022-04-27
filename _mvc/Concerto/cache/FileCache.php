<?php

/**
*   FileCache
*
*   @version 220317
*/

declare(strict_types=1);

namespace Concerto\cache;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Psr\SimpleCache\CacheInterface;
use Concerto\cache\{
    CacheException,
    SimpleCacheTrait,
};

class FileCache implements CacheInterface
{
    use SimpleCacheTrait;

    /**
    *   dir
    *
    *   @var string
    */
    protected string $dir;

    /**
    *   __construct
    *
    *   @param string $dir
    */
    public function __construct(
        ?string $dir = null,
    ) {
        $this->dir = $dir ?? sys_get_temp_dir();
    }

    /**
    *   {inherit}
    */
    public function get(
        string $key,
        mixed $default = null
    ): mixed {
        $this->validateKey($key);
        $this->dispose();

        $file_name = $this->buildFileName($key);

        if (!file_exists($file_name)) {
            return $default;
        }

        $content = file_get_contents($file_name);

        if ($content === false) {
            throw new CacheException(
                "file read error:{$file_name}",
            );
        }

        return unserialize($content);
    }

    /**
    *   {inherit}
    */
    public function set(
        string $key,
        mixed $value,
        null|int|\DateInterval $ttl = null
    ): bool {
        $this->validateKey($key);
        $this->dispose();
        $expires = $this->parseExpire($ttl);

        $file_name = $this->buildFileName($key);

        $serialized = serialize($value);

        if ($serialized === false) {
            throw new CacheException(
                "data decode error:{$file_name}",
            );
        }

        $length = file_put_contents(
            $file_name,
            $serialized,
            LOCK_EX,
        );

        if ($length === false) {
            throw new CacheException(
                "data save error:{$file_name}",
            );
        }

        $is_touched = touch(
            $file_name,
            empty($expires) ?
                time() : time() - (int)$expires
        );


        if ($is_touched === false) {
            $this->delete($key);

            throw new CacheException(
                "expire set error:{$file_name}",
            );
        }
        return true;
    }

    /**
    *   {inherit}
    */
    public function delete(
        string $key
    ): bool {
        $file_name = $this->buildFileName($key);
        return unlink($file_name);
    }

    /**
    *   {inherit}
    */
    public function clear(): bool
    {
        $itelator =
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(
                    $this->dir,
                    FilesystemIterator::KEY_AS_PATHNAME |
                    FilesystemIterator::CURRENT_AS_FILEINFO |
                    FilesystemIterator::SKIP_DOTS
                ),
                RecursiveIteratorIterator::CHILD_FIRST,
            );

        $error = false;

        foreach ($itelator as $path => $file_object) {
            $is_deleted = $file_object->isDir() ?
                rmdir($path) :
                unlink($path);

            if ($is_deleted === false) {
                $error = true;
            }
        }
        return !$error;
    }

    /**
    *   buildFileName
    *
    *   @param string $key
    *   @return string
    */
    protected function buildFileName(
        string $key,
    ): string {
        return $this->dir .
            DIRECTORY_SEPARATOR .
            $key;
    }

    /**
    *   dispose
    *
    *   @return void
    */
    protected function dispose(): void
    {
        $itelator = new RecursiveDirectoryIterator(
            $this->dir,
            FilesystemIterator::KEY_AS_PATHNAME |
            FilesystemIterator::CURRENT_AS_FILEINFO |
            FilesystemIterator::SKIP_DOTS
        );

        $now = time();

        foreach ($itelator as $file_name => $file_object) {
            if ($file_object->isDir()) {
                continue;
            }

            $mtime = $file_object->getMTime();

            if ($mtime === false) {
                throw new CacheException(
                    "mtime error:{$file_name}",
                );
            }

            if ($mtime < $now) {
                $is_deleted = unlink($file_name);
                if ($is_deleted === false) {
                    throw new CacheException(
                        "expired file delete error:{$file_name}",
                    );
                }
            }
        }
    }
}
