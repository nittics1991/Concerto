<?php

/**
 *   ConfigReaderJson
 *
 * @version 190626
 */

declare(strict_types=1);

namespace Concerto\conf;

use RuntimeException;
use Concerto\conf\AbstractConfigReader;

class ConfigReaderJson extends AbstractConfigReader
{
    /**
     *   {inherit}
     */
    public function read(): array
    {
        $data = json_decode((string)file_get_contents($this->file), true);

        if (!is_array($data)) {
            throw new RuntimeException(
                "config file read error"
            );
        }
        return $data;
    }
}
