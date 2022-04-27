<?php

/**
 *   設定リーダー
 *
 * @version 190626
 */

declare(strict_types=1);

namespace Concerto\conf;

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
