<?php

/**
*   FilePathInterface
*
*   @version
*/

declare(strict_types=1);

namespace Concerto\filesystem;

class UserTable
{
    public int $sid;
    public string $oid;
    public string $schemaname;
    public string $relname;
    public int $seq_scan;
    public int $seq_tup_read;
    public int $idx_scan;
    public int $idx_tup_fetch;
    public string $n_tup_ins;
    public string $n_tup_upd;
    public string $n_tup_del;
    public string $n_tup_hot_upd;
    public string $n_live_tup;
    public string $n_dead_tup;
    public DateTimeImmutable $last_vacuum;
    public DateTimeImmutable $last_autovacuum;
    public DateTimeImmutable $last_analyze;
    public DateTimeImmutable $last_autoanalyze;
    public int $vacuum_count;
    public int $autovacuum_count;
    public int $analyze_count;
    public int $autoanalyze_count;
    
    
    
    
    
    
    
    
    

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
