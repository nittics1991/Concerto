<?php

/**
 *   ConfigReaderArray
 *
 * @version 190626
 */

declare(strict_types=1);

namespace Concerto\conf;

use RuntimeException;
use Concerto\conf\AbstractConfigReader;

class ConfigReaderArray extends AbstractConfigReader
{
    /**
     *   {inherit}
     */
    public function read(): array
    {
        $data = $this->doRead();
        
        if (!is_array($data)) {
            throw new RuntimeException(
                "config file read error"
            );
        }
        return $data;
    }
    
    /**
     *   doRead
     */
    private function doRead()
    {
        return include $this->file;
    }
}
