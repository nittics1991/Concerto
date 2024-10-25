<?php

/**
*   FilePathInterface
*
*   @version
*/

declare(strict_types=1);

namespace Concerto\filesystem;

interface FilePathInterface
{
    /*
    *   baseName
    *
    *   @param string $file
    *   @return string
    */
    public static function baseName(
        string $file,
    ): string;

    /*
    *   canonicalize
    *
    *   @param string $file
    *   @return string
    */
    public static function canonicalize(
        string $file,
    ): string;

    /*
    *   extensionName
    *
    *   @param string $file
    *   @return string
    */
    public static function extensionName(
        string $file,
    ): string;

    /*
    *   fileName
    *
    *   @param string $file
    *   @return string
    */
    public static function fileName(
        string $file,
    ): string;

    /*
    *   fromArray
    *
    *   @param array $pieces
    *   @return string
    */
    public static function fromArray(
        array $pieces,
    ): string;

    /*
    *   parentName
    *
    *   @param string $file
    *   @param string $depth 1:dirname 2:dirname(dirname),...
    *   @return string
    */
    public static function parentName(
        string $file,
        int $depth = 1,
    ): string;

    /*
    *   rootName
    *
    *   @param string $file
    *   @return string
    */
    public static function rootName(
        string $file,
    ): string;

    /*
    *   tempFileName
    *
    *   @param string $dir default static::tempDir()
    *   @param string $prefix
    *   @param string $suffix
    *   @return string
    */
    public static function tempFileName(
        string $dir = '',
        string $prefix = '',
        string $suffix = ''
    ): string;

    /*
    *   tempDir
    *
    *   @return string
    */
    public static function tempDir(): string;

    /*
    *   toArray
    *
    *   @param array $path
    *   @return string
    */
    public static function toArray(
        string $path,
    ): array;
}
