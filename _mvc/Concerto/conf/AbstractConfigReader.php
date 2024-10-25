<?php

/**
*   設定リーダー
*
*   @version 230117
*/

declare(strict_types=1);

namespace Concerto\conf;

use InvalidArgumentException;
use Concerto\conf\ConfigReaderInterface;

abstract class AbstractConfigReader implements ConfigReaderInterface
{
    /**
    *   @var string
    */
    protected string $file;

    /**
    *   __construct
    *
    *   @param string $file
    */
    public function __construct(
        string $file
    ) {
        if (!file_exists($file)) {
            throw new InvalidArgumentException(
                "file not found"
            );
        }

        $this->file = $file;
    }

    /**
    *   @inheritDoc
    */
    abstract public function read(): array;
}
