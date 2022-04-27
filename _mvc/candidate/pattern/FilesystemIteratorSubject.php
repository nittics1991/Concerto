<?php

/**
*   Filesystem Iterator ObjectStorageSubject
*
*   @version 151215
*/

declare(strict_types=1);

namespace candidate\pattern;

use Exception;
use FilesystemIterator;
use InvalidArgumentException;
use RuntimeException;
use SplFileInfo;
use SplFileObject;
use SplObserver;
use SplSubject;
use candidate\pattern\ObjectStorageSubject;

class FilesystemIteratorSubject extends ObjectStorageSubject
{
    /**
    *   __construct
    *
    *   @param string $path パス
    */
    public function __construct(string $path)
    {
        parent::__construct();
        $this->attachFilesystem($path);
    }

    /**
    *   ファイル一括アタッチ
    *
    *   @param string $path パス
    */
    public function attachFilesystem(string $path): void
    {
        if (!file_exists($path)) {
            throw new InvalidArgumentException("PATH not found");
        }

        $iterator = new FilesystemIterator(
            $path,
            FilesystemIterator::CURRENT_AS_FILEINFO
        );

        foreach ($iterator as $fileInfo) {
            if (!$fileInfo->isDir()) {
                $class = $fileInfo->getBaseName(".{$fileInfo->getExtension()}");

                try {
                    $namespace = $this->getNamespace($fileInfo);
                    $target = "{$namespace}{$class}";
                    $observer = new $target();
                    $this->attach($observer);
                } catch (Exception $e) {
                    throw new RuntimeException(
                        'attach error:' . $fileInfo->getBasename(),
                        0,
                        $e
                    );
                }
            }
        }
    }

    /**
    *   namespace取得
    *
    *   @param SplFileInfo $fileInfo パス
    *   @return string
    */
    protected function getNamespace(SplFileInfo $fileInfo): string
    {
        $namespace = '';

        try {
            $fileObject = new SplFileObject((string)$fileInfo->getRealPath());

            while ($fileObject->valid()) {
                $data  = (string)$fileObject->fgets();

                if (mb_ereg('^namespace(\s|\t)', $data) > 0) {
                    $result = trim(mb_substr($data, mb_strlen('namespace')));
                    $result = mb_substr($result, 0, mb_strlen($result) - 1);
                    $namespace = "{$result}\\";
                    break;
                }
            }
        } catch (Exception $e) {
            throw new RuntimeException(
                'getNamespace error:' . $fileInfo->getBasename(),
                0,
                $e
            );
        }
        $fileObject = null;
        return $namespace;
    }
}
