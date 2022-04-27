<?php

/**
*   TempDirTestHelper
*
*   @version 220317
*/

declare(strict_types=1);

namespace test\Concerto;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;

class TempDirTestHelper
{
    /**
    *   @var string
    */
    protected string $root_path;

    /**
    *   __construct
    *
    *   @param ?string $path
    *   @param int $permissions
    *   @param bool $recursive
    *   @param ?resource $context
    */
    public function __construct(
        ?string $path = null,
        int $permissions = 0777,
        bool $recursive = true,
        $context = null,
    ) {
        $this->root_path = $path ?? sys_get_temp_dir();

        if (file_exists($this->root_path)) {
            return;
        }

        $result = mkdir(
            $this->root_path,
            $permissions,
            $recursive,
            $context,
        );

        if ($result === false) {
            throw new RuntimeException(
                "temp dir create error:{$this->root_path}"
            );
        }
    }

    /**
    *   create
    *
    *   @param ?string $path
    *   @param int $permissions
    *   @param bool $recursive
    *   @param ?resource $context
    *   @return self
    */
    public static function create(
        ?string $path = null,
        int $permissions = 0777,
        bool $recursive = true,
        $context = null,
    ): self {
        return new self(
            $path,
            $permissions,
            $recursive,
            $context,
        );
    }

    /**
    *   clean
    *
    *   @return static
    */
    public function clean(): static
    {
        $itelator =
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(
                    $this->root_path,
                    FilesystemIterator::KEY_AS_PATHNAME |
                    FilesystemIterator::CURRENT_AS_FILEINFO |
                    FilesystemIterator::SKIP_DOTS
                ),
                RecursiveIteratorIterator::CHILD_FIRST,
            );

        foreach ($itelator as $path => $fileInfo) {
            $is_deleted = $fileInfo->isDir() ?
                rmdir($path) :
                unlink($path);

            if ($is_deleted === false) {
                throw new RuntimeException(
                    "clear error:{$path}"
                );
            }
        }
        return $this;
    }

    /**
    *   root
    *
    *   @return string
    */
    public function root(): string
    {
        return $this->root_path;
    }

    /**
    *   isEmptyDir
    *
    *   @param string $path
    *   @return bool
    */
    public function isEmptyDir(
        string $path,
    ): bool {
        $itelator = new RecursiveDirectoryIterator(
            $this->root_path,
            FilesystemIterator::CURRENT_AS_PATHNAME |
            FilesystemIterator::SKIP_DOTS
        );

        foreach ($itelator as $fileInfo) {
            return false;
        }
        return true;
    }
}
