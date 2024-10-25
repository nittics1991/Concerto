<?php

/**
*   StandardFilesystem
*
*   @version 220226
*
*       $context[
*           'encode_from' => encode_name,
*           'encode_to' => encode_name,
*           'decode_from' => decode_name,
*           'decode_to' => decode_name,
*       ]
*/

declare(strict_types=1);

namespace Concerto\filesystem\implement;

use RuntimeException;
use Concerto\filesystem\FilesystemInterface;

class StandardFilesystem implements FilesystemInterface
{
    /*
    *   {inherit}
    */
    public function chdir(
        string $dir,
        array $context = [],
    ): void {
        $result = chdir(
            $this->resolveEncode(
                $dir,
                $context,
            ),
        );
        
        if ($result === false) {
            throw new RuntimeException(
                "failure chdir:{$dir}"
            );
        }
    }

    /*
    *   {inherit}
    */
    public function chgrp(
        string $file,
        string | int $group,
        array $context = [],
    ): void {
        $result = chgrp(
            $this->resolveEncode(
                $file,
                $context,
            ),
            $group,
        );
        
        if ($result === false) {
            throw new RuntimeException(
                "failure chgrp:{$file}"
            );
        }
    }

    /*
    *   {inherit}
    */
    public function chmod(
        string $file,
        string | int $mode,
        int $umask = 0,
        array $context = [],
    ): void {
        $result = chmod(
            $this->resolveEncode(
                $file,
                $context,
            ),
            is_int($mode)?
                $mode:base_convert($mode, 8, 8),
            $umask,
        );
        
        if ($result === false) {
            throw new RuntimeException(
                "failure chmod:{$file}"
            );
        }
    }

    /*
    *   {inherit}
    */
    public function chown(
        string $file,
        string $user,
        array $context = [],
    ): void {
        $result = chown(
            $this->resolveEncode(
                $file,
                $user,
                $context,
            ),
        );
        
        if ($result === false) {
            throw new RuntimeException(
                "failure chown:{$file}"
            );
        }
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
        $result = chown(
            $this->resolveEncode(
                $originFile,
                $targetFile,
                $overwriteNewerFiles,
                $context,
            ),
        );
        
        if ($result === false) {
            throw new RuntimeException(
                "failure chown:{$file}"
            );
        }
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
        string | int | null $mode,
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







    /*
    *   resolveEncode
    *   
    *   @param string $path
    *   @param mixed[] $context
    *   @return string
    */
    protected function resolveEncode(
        string $path,
        array $context,
    ): string {
        return $this->convert(
            $path,
            $context,
            'encode',
        );
    }

    /*
    *   resolveDecode
    *   
    *   @param string $path
    *   @param mixed[] $context
    *   @return string
    */
    protected function resolveDecode(
        string $path,
        array $context,
    ): string {
        return $this->convert(
            $path,
            $context,
            'decode',
        );
    }

    /*
    *   convert
    *   
    *   @param string $path
    *   @param mixed[] $context
    *   @param string $type encode|decode
    *   @return string
    */
    protected function convert(
        string $path,
        array $context,
        string $type,
    ): string {
        if (
            !array_key_exists("{$type}_from", $context) &&
            !array_key_exists("{$type}_to", $context)
        ) {
            return $path;
        }
                
        $from = $context["{$type}_from"]?? 'UTF-8';
        $to = $context["{$type}_to"]?? 'UTF-8';
        
        $result = mb_convert_encoding(
            $path,
            $to,
            $from,
        );
        
        if ($result === false) {
            throw new RuntimeException(
                "convert error"
            );
        }
        return $result;
    }   
}
