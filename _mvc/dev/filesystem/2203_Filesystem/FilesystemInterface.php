<?php

/**
*   FilesystemInterface
*
*   @version
*/

declare(strict_types=1);

namespace Concerto\filesystem;

interface FilesystemInterface
{
    /*
    *   chdir
    *
    *   @param string $dir
    *   @param mixed[] $context
    *   @return void
    */
    public function chdir(
        string $dir,
        array $context,
    ): void;

    /*
    *   chgrp
    *
    *   @param string $file
    *   @param string|int $group
    *   @param mixed[] $context
    *   @return void
    */
    public function chgrp(
        string $file,
        string | int $group,
        array $context,
    ): void;

    /*
    *   chmod
    *
    *   @param string $file
    *   @param string|int $mode
    *   @param int $umask
    *   @param mixed[] $context
    *   @return void
    */
    public function chmod(
        string $file,
        string | int $mode,
        int $umask,
        array $context,
    ): void;

    /*
    *   chown
    *
    *   @param string $file
    *   @param string $user
    *   @param mixed[] $context
    *   @return void
    */
    public function chown(
        string $file,
        string $user,
        array $context,
    ): void;

    /*
    *   copy
    *
    *   @param string $originFile
    *   @param string $targetFile
    *   @param bool $overwriteNewerFiles
    *   @param mixed[] $context
    *   @return void
    */
    public function copy(
        string $originFile,
        string $targetFile,
        bool $overwriteNewerFiles,
        array $context,
    ): void;

    /*
    *   exists
    *
    *   @param string $file
    *   @param mixed[] $context
    *   @return bool
    */
    public function exists(
        string $file,
        array $context,
    ): bool;

    /*
    *   mkdir
    *
    *   @param string $dirs
    *   @param string|int|null $mode
    *   @param mixed[] $context
    *   @return void
    */
    public function mkdir(
        string $dirs,
        string | int | null $mode,
        array $context,
    ): void;

    /*
    *   pwd
    *
    *   @return string
    */
    public function pwd(): string;

    /*
    *   readlink
    *
    *   @param string $path
    *   @param mixed[] $context
    *   @return string
    */
    public function readlink(
        string $path,
        array $context,
    ): string;

    /*
    *   realpath
    *
    *   @param string $path
    *   @param mixed[] $context
    *   @return string
    */
    public function realpath(
        string $path,
        array $context,
    ): string;

    /*
    *   remove
    *
    *   @param string $file
    *   @param mixed[] $context
    *   @return void
    */
    public function remove(
        string $file,
        array $context,
    ): void;

    /*
    *   rename
    *
    *   @param string $origin
    *   @param string $target
    *   @param mixed[] $context
    *   @return void
    */
    public function rename(
        string $origin,
        string $target,
        array $context,
    ): void;

    /*
    *   symlink
    *
    *   @param string $target
    *   @param string $link
    *   @param mixed[] $context
    *   @return void
    */
    public function symlink(
        string $target,
        string $link,
        array $context,
    ): void;

    /*
    *   touch
    *
    *   @param string $file
    *   @param ?int $time
    *   @param ?int $atime
    *   @param mixed[] $context
    *   @return void
    */
    public function touch(
        string $file,
        ?int $time = null,
        ?int $atime = null,
        array $context,
    ): void;
}
