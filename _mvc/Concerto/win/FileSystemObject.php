<?php

/**
*   FSO
*
*   @version 221219
*/

declare(strict_types=1);

namespace Concerto\win;

use COM;
use Exception;

class FileSystemObject
{
    /**
    *   @var COM
    */
    protected COM $com;

    /**
    *   __construct
    *
    */
    public function __construct()
    {
        $this->com = new COM(
            'Scripting.FileSystemObject',
            null,
            CP_UTF8
        );
    }

    /**
    *   __destruct
    *
    */
    public function __destruct()
    {
        unset($this->com);
    }

    /**
    *   ディレクトリリスト
    *
    *   @param ?string $path パス
    *   @return string[]
    */
    public function dir(
        ?string $path = null
    ): array {
        $src = is_null($path) ? getcwd() : $path;

        $folder = $this->com->GetFolder($src);

        $result = [];

        $subfolders = $folder->SubFolders();

        foreach ($subfolders as $folder2) {
            $result[] = $folder2->Path . DIRECTORY_SEPARATOR;
        }

        $files = $folder->Files;

        foreach ($files as $file) {
            $result[] = $file->Path;
        }

        return $result;
    }

    /**
    *   再帰ディレクトリリスト
    *
    *   @param ?string $path パス
    *   @return string[]
    */
    public function recursiveDir(
        ?string $path = null
    ): array {
        $src = is_null($path) ? getcwd() : $path;

        $folder = $this->com->GetFolder($src);

        $result = [];

        $subfolders = $folder->SubFolders();

        foreach ($subfolders as $folder2) {
            $result[] = $folder2->Path . DIRECTORY_SEPARATOR;

            $result = array_merge(
                $result,
                $this->recursiveDir($folder2->Path)
            );
        }

        $files = $folder->Files;

        foreach ($files as $file) {
            $result[] = $file->Path;
        }

        return $result;
    }
}
