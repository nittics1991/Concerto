<?php

/**
*   FilesystemInterface
*
*   @version 210922
*/

declare(strict_types=1);

namespace candidate\util;

interface FilesystemInterface
{
    /*
    *   copy
    *
    *   @param string $originFile
    *   @param string $targetFile
    *   @param ?bool $overwriteNewerFiles
    *   @return void
    */
    public static function copy(
        string $originFile,
        string $targetFile,
        ?bool $overwriteNewerFiles = false
    ): void;

    /*
    *   mkdir
    *
    *   @param string $dirs
    *   @param string|int|null $mode
    *   @return void
    */
    public static function mkdir(
        string $dirs,
        string | int | null $mode = 0777
    ): void;

    /*
    *   exists
    *
    *   @param string $file
    *   @return
    */
    public static function exists(
        string $file
    );

    /*
    *   touch
    *
    *   @param string $file
    *   @param ?int $time
    *   @param ?int $atime
    *   @return string
    */
    public static function touch(
        string $file,
        ?int $time = null,
        ?int $atime = null
    ): string;

    /*
    *   remove
    *
    *   @param string $file
    *   @return string
    */
    public static function remove(
        string $file
    ): string;

    /*
    *   chmod
    *
    *   @param string $files
    *   @param int $mode
    *   @param ?int $umask
    *   @param ?bool $recursive
    *   @return void
    */
    public static function chmod(
        string $files,
        int $mode,
        ?int $umask = 0000,
        ?bool $recursive = false
    ): void;

    /*
    *   chown
    *
    *   @param string $files
    *   @param string $user
    *   @param bool $recursive
    *   @return void
    */
    public static function chown(
        string $files,
        string $user,
        bool $recursive = false
    ): void;

    /*
    *   chgrp
    *
    *   @param string $files
    *   @param string|int $group
    *   @param bool $recursive
    *   @return void
    */
    public static function chgrp(
        string $files,
        string | int $group,
        bool $recursive = false
    ): void;

    /*
    *   rename
    *
    *   @param string $origin
    *   @param string $target
    *   @param bool $overwrite
    *   @return string
    */
    public static function rename(
        string $origin,
        string $target,
        bool $overwrite = false
    ): string;

    /*
    *   symlink
    *
    *   @param string $target
    *   @param string $link
    *   @return void
    */
    public static function symlink(
        string $target,
        string $link
    ): void;

    /*
    *   readlink
    *
    *   @param string $path
    *   @return string
    */
    public static function readlink(
        string $path
    ): string;

    /*
    *   realpath
    *
    *   @param string $path
    *   @return string
    */
    public static function realpath(
        string $path,
    ): string;

    /*
    *   mirror
    *
    *   @param string $originDir
    *   @param string $targetDir
    *   @param ?\Traversable $iterator
    *   @param ?array $options
    *   @return void
    */
    public static function mirror(
        string $originDir,
        string $targetDir,
        ?\Traversable $iterator = null,
        ?array $options = []
    ): void;

    /*
    *   isAbsolutePath
    *
    *   @param string $file
    *   @return bool
    */
    public static function isAbsolutePath(
        string $file
    ): bool;


    /*
    *   tempnam
    *
    *   @param string $dir,
    *   @param ?string $prefix
    *   @param ?string $suffix
    *   @return string
    */
    public static function tempnam(
        string $dir,
        ?string $prefix = '',
        ?string $suffix = ''
    ): string;
}
