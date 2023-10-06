<?php

/**
*   Filesystem
*
*   @version 220226
*/

declare(strict_types=1);

namespace Concerto\filesystem;

use Concerto\filesystem\FilesystemInterface;

class Filesystem implements FilesystemInterface
{
    /*
    *   @var FilesystemInterface
    */
    protected FilesystemInterface $adapter;

    /*
    *   {inherit}
    */
    public function __construct(
        FilesystemInterface $adapter,
    ) {
        $this->adapter = $adapter;
    }

    /*
    *   {inherit}
    */
    public function chdir(
        string $dir,
        array $context = [],
    ): void {
        $this->adapter->chdir(
            $dir,
            $context,
        );
    }

    /*
    *   {inherit}
    */
    public function chgrp(
        string $file,
        string | int $group,
        array $context = [],
    ): void {
        $this->adapter->chgrp(
            $file,
            $group,
            $context,
        );
    }

    /*
    *   {inherit}
    */
    public function chmod(
        string $file,
        string | int $mode,
        int $umask,
        array $context = [],
    ): void {
        $this->adapter->chmod(
            $file,
            $mode,
            $umask,
            $context,
        );
    }

    /*
    *   {inherit}
    */
    public function chown(
        string $file,
        string $user,
        array $context = [],
    ): void {
        $this->adapter->chown(
            $file,
            $user,
            $context,
        );
    }

    /*
    *   {inherit}
    */
    public function copy(
        string $originFile,
        string $targetFile,
        bool $overwriteNewerFiles,
        array $context = [],
    ): void {
        $this->adapter->copy(
            $originFile,
            $targetFile,
            $overwriteNewerFiles,
            $context,
        );
    }

    /*
    *   {inherit}
    */
    public function exists(
        string $file,
        array $context = [],
    ): bool {
        return $this->adapter->exists(
            $file,
            $context,
        );
    }

    /*
    *   {inherit}
    */
    public function mkdir(
        string $dirs,
        string | int | null $mode = null,
        array $context = [],
    ): void {
        $this->adapter->mkdir(
            $dirs,
            $mode,
            $context,
        );
    }

    /*
    *   {inherit}
    */
    public function pwd(): string
    {
        return $this->adapter->pwd();
    }

    /*
    *   {inherit}
    */
    public function readlink(
        string $path,
        array $context = [],
    ): string {
        return $this->adapter->readlink(
            $path,
            $context,
        );
    }

    /*
    *   {inherit}
    */
    public function realpath(
        string $path,
        array $context = [],
    ): string {
        return $this->adapter->realpath(
            $path,
            $context,
        );
    }

    /*
    *   {inherit}
    */
    public function remove(
        string $file,
        array $context = [],
    ): void {
        $this->adapter->remove(
            $file,
            $context,
     );
    }

    /*
    *   {inherit}
    */
    public function rename(
        string $origin,
        string $target,
        array $context = [],
    ): void {
        $this->adapter->rename(
            $origin,
            $target,
            $context,
        );
    }

    /*
    *   {inherit}
    */
    public function symlink(
        string $target,
        string $link,
        array $context = [],
    ): void {
        $this->adapter->symlink(
            $target,
            $link,
            $context,
        );
    }

    /*
    *   {inherit}
    */
    public function touch(
        string $file,
        ?int $time = null,
        ?int $atime = null,
        array $context = [],
    ): void {
        $this->adapter->touch(
            $file,
            $time,
            $atime,
            $context,
        );
    }
}
