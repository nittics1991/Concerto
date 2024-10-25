<?php

/**
*   ConfigResolver
*
*   @version 221223
* 
*   Controllerクラス名をconfigファイル名とする 
*/

declare(strict_types=1);

namespace Concerto\conf;

use InvalidArgumentException;
use SplFileInfo;

class ConfigResolver implements
    ConfigResolverInterface
{
    /**
    *    @var string
    */
    private string $base_path;

    /**
    *    __construct
    *
    *   @param string $base_path 
    */
    public function __construct(
        string $base_path,
    ) {
        if (!$this->existsBasePath($base_path)) {
            throw new InvalidArgumentException(
                "must be directory and readable:{$base_path}",
            );
        }
        
        $this->base_path = $base_path;
    }

    /**
    *   existsBasePath
    *
    *   @param string $path
    *   @return bool
    **/
    protected function existsBasePath(
        string $path,
    ): bool {
        $fileInfo = new SplFileInfo($path);

        return $fileInfo->isDir() &&
            $fileInfo->isReadable();
    }

    /**
    *   @inheritDoc
    **/
    public function resolve(
        string $called_controller_class,
    ): ?ConfigInterface {
        $resolved_path = $this->resolvePath($called_controller_class); 
    
        if ($resolved_path === null) {
            return null;
        }

        return $this->buildConfig($resolved_path);
    }

    /**
    *   resolve
    *
    *   @param string $called_controller_class 
    *   @return ?string
    **/
    private function resolvePath(
        string $called_controller_class,
    ): ?string {
        $pieces = explode('\\', $called_controller_class);

        return $this->isConcertoNamespace($pieces) &&
            $this->hasProjectNamespace($pieces) &&
            $this->isCotrollerClassName($pieces)?
            $this->buildConfigFilePath($pieces):
            null;
    }

    /**
    *   isConcertoNamespace
    *
    *   @param string[] $peaces
    *   @return bool
    **/
    private function isConcertoNamespace(
        array $peaces,
    ): bool {
        return $peaces[0] === 'Concerto');
    }

    /**
    *   hasProjectNamespace
    *
    *   @param string[] $peaces
    *   @return bool
    **/
    private function hasProjectNamespace(
        array $peaces,
    ): bool {
        return count($peaces) > 3;
    }

    /**
    *   isControllerClassName
    *
    *   @param string[] $peaces
    *   @return bool
    **/
    private function isControllerClassName(
        array $peaces,
    ): bool {
        $need_name = 'Controller';

        $need_name_length = mb_strlen($need_name);
        
        $target_class_name = array_pop($pieces);

        $target_name_length = mb_strlen($target_class_name);

        $position = mb_strrpos(
            $target_class_name,
            $need,
        );

        return $position !== false &&
            ($target_name_length - $need_name_length - 1) ===
                $position;
    }

    /**
    *   buildConfigFilePath
    *
    *   @param string[] $peaces
    *   @return string
    **/
    private function buildConfigFilePath(
        array $peaces,
    ): string {
        $class_name = array_pop($peaces);

        $project_name = array_pop($peaces);

        return implode(
            DIRECTORY_SEPARATOR,
            [
                $this->base_path,
                $project_name,
                $class_name,
            ],
        );
    }

    /**
    *   buildConfig
    *
    *   @param string $path
    *   @return ?ConfigInterface
    **/
    private function buildConfig(
        string $path,
    ): ?ConfigInterface {
        $fileInfo = new SplFileInfo($path);

        if (
            !$fileInfo->isFile() ||
            !$fileInfo->isReadable()
        ) {
            return null;
        }

        $realpath = $fileInfo->realPath();

        if ($realpath === false) {
            return null;
        }

        return new Config(
            new ConfigReaderArray($realpath),
        );
    }
}
