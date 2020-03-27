<?php

/**
 *   ConfigReaderXml
 *
 * @version 191210
 */

declare(strict_types=1);

namespace Concerto\conf\conf;

use RuntimeException;
use Concerto\conf\conf\{
    Config,
    ConfigInterface,
    ConfigReaderInterface
};

class ConfigReaderXml implements ConfigReaderInterface
{
    /**
     *   {inherit}
     */
    public function build(string $path): ConfigInterface
    {
        $data = json_decode(
            (string)json_encode(
                simplexml_load_file($path)
            ),
            true
        );
        
        if (!is_array($data)) {
            throw new RuntimeException(
                "config file read error"
            );
        }
        return new Config($data);
    }
}
