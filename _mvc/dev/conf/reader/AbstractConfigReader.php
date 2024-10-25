<?php

/**
 *   設定リーダー
 *
 * @version 210822
 */

declare(strict_types=1);

namespace Concerto\conf\reader;

use InvalidArgumentException;
use Concerto\conf\ConfigReaderInterface;

abstract class AbstractConfigReader implements ConfigReaderInterface
{
    /**
     *   ファイル名
     *
     * @var string
     */
    protected $file;

    /**
     *   __construct
     *
     * @param string $file
     */
    public function __construct(string $file)
    {
        if (!file_exists($file)) {
            throw new InvalidArgumentException("file not found");
        }
        $this->file = $file;
    }

    /**
     *   {inherit}
     */
    abstract public function read(): array;
}
