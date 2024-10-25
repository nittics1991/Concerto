<?php

/**
*   FilesystemWsh
*
*   @version
*/

declare(strict_types=1);

namespace Concerto\util\implement;

use COM;
use RuntimeException;
use Concerto\util\FilesystemInterface;

class FilesystemWsh implements FilesystemInterface
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
    ): void {
        $com = self::createFsoComObject();
        $com->CopyFile(
            $originFile,
            $targetFile,
            $overwriteNewerFiles,
        );
    }

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
    ): void {
        $com = self::createFsoComObject();
        $com->CreateFolder(
            $dirs,
        );
    }

    /*
    *   exists
    *
    *   @param string $file
    *   @return bool
    */
    public static function exists(
        string $file
    ): bool {
        $com = self::createFsoComObject();
        return $this->existsFolder($file) ||
        $this->existsFile($file);
    }

    /*
    *   existsFolder
    *
    *   @param string $path
    *   @return bool
    */
    private static function existsFolder(
        string $path
    ): bool {
        $com = self::createFsoComObject();
        return $com->FolderExists($path);
    }

    /*
    *   existsFile
    *
    *   @param string $path
    *   @return bool
    */
    private static function existsFile(
        string $path
    ): bool {
        $com = self::createFsoComObject();
        return $com->FileExists($path);
    }

    /*
    *   touch
    *
    *   @param string $file
    *   @param ?int $time
    *   @param ?int $atime
    *   @return void
    */
    public static function touch(
        string $file,
        ?int $time = null,
        ?int $atime = null
    ): void {
        throw new RuntimeException(
            "not supported"
        );
    }

    /*
    *   remove
    *
    *   @param string $file
    *   @return void
    */
    public static function remove(
        string $file
    ): void {
        $com = self::createFsoComObject();

        if ($this->existsFolder($file)) {
            $com->DeleteFolder($file);
        } else {
            $com->DeleteFile($file);
        }
    }

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
    ): void {
        throw new RuntimeException(
            "not supported"
        );
    }

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
    ): void {
        throw new RuntimeException(
            "not supported"
        );
    }

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
    ): void {
        throw new RuntimeException(
            "not supported"
        );
    }

    /*
    *   rename
    *
    *   @param string $origin
    *   @param string $target
    *   @param bool $overwrite
    *   @return void
    */
    public static function rename(
        string $origin,
        string $target,
        bool $overwrite = false
    ): void {
        $com = self::createFsoComObject();
        $com->MoveFile(
            $origin,
            $target,
        );
    }

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
    ): void {
        throw new RuntimeException(
            "not supported"
        );
    }

    /*
    *   readlink
    *
    *   @param string $path
    *   @return string
    */
    public static function readlink(
        string $path
    ): string {
        throw new BadMethodCallException(
            "not suported"
        );
    }

    /*
    *   realpath
    *
    *   @param string $path
    *   @return string
    */
    public static function realpath(
        string $path,
    ): string {
        throw new BadMethodCallException(
            "not suported"
        );
    }

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
    ): void {
        throw new BadMethodCallException(
            "not suported"
        );
    }

    /*
    *   isAbsolutePath
    *
    *   @param string $file
    *   @return bool
    */
    public static function isAbsolutePath(
        string $file
    ): bool {
        $com = self::createFsoComObject();
        return $com->GetAbsolutePathName($file) ===
        $file;
    }

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
    ): string {
        $com = self::createFsoComObject();
        $temp_name = $com->GetTempName();

        return
        $dir .
        mb_substr($dir, -1, 1) === DIRECTORY_SEPARATOR ?
        '' : DIRECTORY_SEPARATOR .
        $prefix .
        $com->GetBaseName($temp_name) .
        $suffix .
        $com->GetExtension($temp_name);
    }

    /*
    *   createFsoComObject
    *
    *   @return COM
    */
    protected static function createFsoComObject(): COM
    {
        return new COM(
            'Scripting.FileSystemObject',
        );
    }
}
