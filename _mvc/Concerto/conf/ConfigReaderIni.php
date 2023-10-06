<?php

/**
*   ConfigReaderIni
*
*   @version 221206
*/

declare(strict_types=1);

namespace Concerto\conf;

use InvalidArgumentException;
use RuntimeException;
use Concerto\conf\AbstractConfigReader;

class ConfigReaderIni extends AbstractConfigReader
{
    /**
    *   mode
    */
    public const NORMAL = INI_SCANNER_NORMAL;
    public const RAW = INI_SCANNER_RAW;
    public const TYPED = INI_SCANNER_TYPED;

    /**
    *   @var bool
    */
    private bool $recursive = false;

    /**
    * @var int
    */
    private int $mode = INI_SCANNER_NORMAL;

    /**
    *   @inheritDoc
    */
    public function read(): array
    {
        $data = parse_ini_file(
            $this->file,
            $this->recursive,
            $this->mode
        );

        if (!is_array($data)) {
            throw new RuntimeException(
                "config file read error"
            );
        }

        return $data;
    }

    /**
    *   多次元配列として読み込み
    *
    *   return static
    */
    public function recursive(): static
    {
        $this->recursive = true;

        return $this;
    }

    /**
    *   mode
    *
    *   @param  int $param
    *   @return static
    */
    public function mode(
        int $param
    ): static {
        switch ($param) {
            case self::NORMAL:
            case self::RAW:
            case self::TYPED:
                $this->mode = $param;
                break;
            default:
                throw new InvalidArgumentException(
                    "mode invalid:{$param}"
                );
        }

        return $this;
    }
}
