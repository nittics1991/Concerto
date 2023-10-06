<?php

/**
 *   DirectoryConfigReader
 *
 * @version 210822
 */

declare(strict_types=1);

namespace Concerto\conf\reader;

use RuntimeException;
use SplFileInfo;
use Concerto\conf\ConfigReaderInterface;
use Concerto\conf\reader\{
  AbstractConfigReader,
  ConfigReaderArray,
  ConfigReaderIni,
  ConfigReaderJson,
  ConfigReaderXml,
};

class DirectoryConfigReader implements ConfigReaderInterface
{
    /**
     * @var array
     */
    private array $readers = [
    '' => 'AbstractConfigReader',
    'php' => 'ConfigReaderArray',
    'ini' => 'ConfigReaderIni',
    'json' => 'ConfigReaderJson',
    'xml' => 'ConfigReaderXml',
    ];

    /**
     *  base_path
     *
     * @var string
     */
    protected string $base_path = '';

    /**
     *   __construct
     *
     * @param string $base_path
     */
    public function __construct(
        string $bath_path
    ) {
        $this->base_path = $base_path;
    }

    /**
     *   {inherit}
     */
    public function read(): array
    {
        $this->errors = [];
        $config_dataset = [];

        $fileInfo = new SplFileInfo($base_path);

        if (!fileInfo->isDir()) {
            throw new RuntimeException(
                "bath path is not exists:{$this->base_path}"
            );
        }

        $directory = new RecursiveDirectoryIterator(
            $base_path,
            FilesystemIterator::KEY_AS_PATHNAME |
            FilesystemIterator::CURRENT_AS_FILEINFO |
            FilesystemIterator::SKIP_DOTS
        );

        foreach ($directory as $path => $config_file) {
            if ($config_file->isDir()) {
                continue;
            }

            if (!$config_file->isReadable()) {
                $this->errors[] = $config_file->getRealPath();
                continue;
            }

            $reader_fdqn = $this->fileInfoToFqdn(
                $config_file
            );

            if ($reader_fdqn === '') {
                $this->errors[] = $config_file->getRealPath();
                continue;
            };

            $config_dataset[] = $this->readConfigFile(
                $config_file,
                $reader_fdqn
            );
        }

        return $config_dataset;
    }

    /**
     *   fileInfoToFqdn
     *
     * @param  SplFileInfo $fileInfo
     * @return string
     */
    protected function fileInfoToFqdn(
        SplFileInfo $fileInfo
    ): string {
        $extension = $fileInfo->getExtension();

        if (
            !array_key_exists(
                $extension,
                $this->readers,
            )
        ) {
            return '';
        }

        $reflectionClass = new ReflectionClass(
            $this->readers[$extension]
        );

        return $reflectionClass->getName();
    }

    /**
     *    readConfigFile
     *
     * @param  SplFileInfo $fileInfo
     * @param  string      $reader_fdqn
     * @return array
     */
    protected function readConfigFile(
        SplFileInfo $fileInfo,
        string $reader_fdqn,
    ): array {
        $reader = new $reader_fdqn(
            $fileInfo->getName(),
        );

        $bug = $this->fileInfoToBug($fileInfo);
        $bug[] = $reader->read();

        return $bug;
    }

    /**
     *    fileInfoToBug
     *
     * @param  SplFileInfo $fileInfo
     * @return array
     */
    protected function fileInfoToBug(
        SplFileInfo $fileInfo
    ): array {
        $paths = explode(
            DIRECTORY_SEPARATOR,
            $fileInfo->getPath(),
        );

        $bug = [];
        foreach ($paths as $path_name) {
            $bug[$path_name] = [];
        }

        return $bug;
    }
}
