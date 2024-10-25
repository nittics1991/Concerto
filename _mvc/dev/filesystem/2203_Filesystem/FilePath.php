<?php

/**
*   FilePath
*
*   @version 220319
*/

declare(strict_types=1);

namespace Concerto\filesystem;

use InvalidArgumentException;
use RuntimeException;
use Concerto\filesystem\FilePathInterface;

class FilePath implements FilePathInterface
{
    /*
    *   {inherit}
    */
    public static function baseName(
        string $file,
    ): string{
        $file_pieces = static::createFilePieces($file);
        
        if (count($file_pieces) <= 2) {
            return $file_pieces[0];
        }
            
        array_pop($file_pieces);
        
        return implode(
            '.',
            $file_pieces
        );
    }

    /*
    *   {inherit}
    */
    public static function canonicalize(
        string $file,
    ): string{
        $replacement = DIRECTORY_SEPARATOR === '/'?
            '\\':'/';
        
        $replaced = mb_ereg_replace(
            '.*' . static::separatePattern(), 
            $replacement,
            $file,
        );
        
        if (!is_string($replaced)) {
            throw new RuntimeException(
                "separater replace failure;{$file}"
            );
        }
        
        $path_pieces = static::toArray($replaced);
        $real_paths = [];
        
        foreach($path_pieces as $piece) {
            if ($piece === '.') {
                //nop
            } elseif($piece === '..') {
                array_pop($real_paths);
            } else {
                $real_paths[] = $piece;
            }
        }
        
        return static::fromArray($real_paths);
    }

    /*
    *   {inherit}
    */
    public static function extensionName(
        string $file,
    ): string{
        $file_pieces = static::createFilePieces($file);
        return count($file_pieces) > 1?
            $file_pieces[count($file_pieces) - 1]:'';
    }

    /*
    *   {inherit}
    */
    public static function fileName(
        string $file,
    ): string{
        $path_pieces = static::toArray($file);
        return (string)array_pop($path_pieces);
    }

    /*
    *   {inherit}
    */
    public static function fromArray(
        array $pieces,
    ): string{
        return implode(
            DIRECTORY_SEPARATOR,
            $pieces,
        );
    }

    /*
    *   {inherit}
    */
    public static function parentName(
        string $file,
        int $depth = 1,
    ): string{
        $path_pieces = static::toArray($file);
        $pos = count($path_pieces) -1 - $depth;
        
        if (
            $pos < 0 ||
            $path_pieces[$pos] === ''
        ) {
            throw new InvalidArgumentException(
                "not exists name." .
                " file={$file}" .
                " depth={$depth}",
            );
        }
        
        return $path_pieces[$pos];
    }

    /*
    *   {inherit}
    */
    public static function rootName(
        string $file,
    ): string{
        $path_pieces = static::toArray($file);
        $path = '';
        
        foreach($path_pieces as $piece) {
            if ($piece === '') {
                $path .= DIRECTORY_SEPARATOR;
            } else {
                $path .= $piece;
                break;
            }
        }
        
        return $path;
    }

    /*
    *   {inherit}
    */
    public static function tempFileName(
        string $dir = '',
        string $prefix = '',
        string $suffix = ''
    ): string{
        return ($dir === ''? static::tempDir():$dir) .
            DIRECTORY_SEPARATOR .
            $prefix .
            bin2hex(random_bytes(16)) .
            $suffix;
    }

    /*
    *   {inherit}
    */
    public static function tempDir(): string
    {
        return sys_get_temp_dir();
    }

    /*
    *   {inherit}
    */
    public static function toArray(
        string $path,
    ): array{
       $splited = mb_split(
            static::separatePattern(),
            $path,
        );
        
        if ($splited === false) {
            throw new RuntimeException(
                "could not split"
            );
        }
        
        return $splited;
    }

    /*
    *   separatePattern
    * 
    *   @return string
    */
    protected static function separatePattern():string
    {
        return DIRECTORY_SEPARATOR === '/'?
            '/':'\\\\';
    }
    
    /*
    *   createFilePieces
    * 
    *   @param string $path
    *   @return array
    */
    protected static function createFilePieces(
        string $path,
    ):array
    {
        $path_pieces = static::toArray($path);
        $splited = mb_split(
            '\.',
            $path_pieces[count($path_pieces) - 1],
        );
        
        if ($splited === false) {
            throw new RuntimeException(
                "could not split"
            );
        }
        
        return $splited[count($splited) - 1] === ''?
            ['']:$splited;
    }
}
