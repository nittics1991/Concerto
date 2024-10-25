<?php

/**
*   DumpFunctionList
*
*   @version 240819
*/

declare(strict_types=1);

namespace tool\dumpFunctionList;

use InvalidArgumentException;
use FilesystemIterator;
use PhpToken;
use RuntimeException;
use SplFileInfo;

class DumpFunctionList
{
    /**
    *   __construct
    *
    */
    public function __construct()
    {
    }

    /**
    *   __invoke
    *
    *   @param string $path
    *   @return void
    */
    public function __invoke(
        string $path,
    ): void {
        if (
            !file_exists($path) ||
            !is_readable($path)
        ) {
            throw new InvalidArgumentException(
                "invalid file:{$path}"
            );
        }

        $this->execute($path);
    }

    /**
    *   execute
    *
    *   @return void
    */
    private function execute(
        string $path,
    ): void {
        if (is_file($path)) {
            $file = new SplFileInfo($path);
            $this->dump($file);
            return;
        }

        $iterator = new FilesystemIterator(
            $path,
            FilesystemIterator::KEY_AS_PATHNAME |
                FilesystemIterator::CURRENT_AS_FILEINFO |
                FilesystemIterator::SKIP_DOTS
        );

        foreach ($iterator as $path => $file) {
            echo $path;
            echo PHP_EOL;

            if ($file->isFile()) {
                $this->dump($file);
            }
        }
    }

    /**
    *   dump
    *
    *   @param SplFileInfo $file
    *   @return void
    */
    private function dump(
        SplFileInfo $file,
    ): void {
        $contents = file_get_contents(
            (string)$file->getRealPath(),
        );

        if ($contents === false) {
            throw new RuntimeException(
                "file get error:" . $file->getRealPath(),
            );
        }

        $tokens = PhpToken::tokenize($contents);

        foreach ($tokens as $token) {
            if (
                $token->is(T_STRING) &&
                function_exists($token->__toString())
            ) {
                var_dump($token);
                echo "";
            }
        }
    }
}
