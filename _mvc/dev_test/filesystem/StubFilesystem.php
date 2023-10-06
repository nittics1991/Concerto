<?php

/**
*   StubFilesystem
*
*   @version 220226
*/

declare(strict_types=1);

namespace test\Concerto\filesystem;

use Concerto\filesystem\FilesystemInterface;

class StubFilesystem implements FilesystemInterface
{
    /*
    *   __construct
    * 
    *   @param array $return_values
    */
    public function __construct(
        protected array $return_values = [],
    ) {
    }

    /*
    *   makeResult
    * 
    *   @return mixed
    */
    protected function makeResult():mixed
    {
        $result = current($this->return_values);
        next($this->return_values);
        return $result;
    }
    
    /*
    *   {inherit}
    */
    public function chdir(
        string $dir,
        array $context = [],
    ): void {
    }

    /*
    *   {inherit}
    */
    public function chgrp(
        string $file,
        string | int $group,
        array $context = [],
    ): void {
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
    }

    /*
    *   {inherit}
    */
    public function chown(
        string $file,
        string $user,
        array $context = [],
    ): void {
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
    }

    /*
    *   {inherit}
    */
    public function exists(
        string $file,
        array $context = [],
    ): bool {
        return $this->makeResult();
    }

    /*
    *   {inherit}
    */
    public function mkdir(
        string $dirs,
        string | int | null $mode = null,
        array $context = [],
    ): void {
    }

    /*
    *   {inherit}
    */
    public function pwd(): string
    {
        return $this->makeResult();
    }

    /*
    *   {inherit}
    */
    public function readlink(
        string $path,
        array $context = [],
    ): string {
        return $this->makeResult();
    }

    /*
    *   {inherit}
    */
    public function realpath(
        string $path,
        array $context = [],
    ): string {
        return $this->makeResult();
    }

    /*
    *   {inherit}
    */
    public function remove(
        string $file,
        array $context = [],
    ): void {
   }

    /*
    *   {inherit}
    */
    public function rename(
        string $origin,
        string $target,
        array $context = [],
    ): void {
    }

    /*
    *   {inherit}
    */
    public function symlink(
        string $target,
        string $link,
        array $context = [],
    ): void {
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
    }
}
