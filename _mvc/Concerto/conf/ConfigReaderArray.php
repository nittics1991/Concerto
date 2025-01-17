<?php

/**
*   ConfigReaderArray
*
*   @version 221206
*/

declare(strict_types=1);

namespace Concerto\conf;

use RuntimeException;
use Concerto\conf\AbstractConfigReader;

class ConfigReaderArray extends AbstractConfigReader
{
    /**
    *   @inheritDoc
    */
    public function read(): array
    {
        $data = $this->doRead();

        if (!is_array($data)) {
            throw new RuntimeException(
                "config file must be return array"
            );
        }

        return $data;
    }

    /**
    *   doRead
    *
    *   @return mixed[]
    */
    private function doRead(): array
    {
        return include $this->file;
    }
}
