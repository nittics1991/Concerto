<?php

/**
*   MemcacheFactory
*
*   @version 221201
*/

declare(strict_types=1);

namespace Concerto\cache;

use Memcache;
use RuntimeException;

class MemcacheFactory
{
    /**
    *   getConnection
    *
    *   @param string $server
    *   @param int $port
    *   @return Memcache
    */
    public static function getConnection(
        string $server = '127.0.0.1',
        int $port = 11211
    ): Memcache {
        $cache = new Memcache();

        if ($cache->addServer($server, $port) === false) {
            throw new RuntimeException(
                "add server error:{$server}:{$port}"
            );
        }

        if ($cache->connect($server, $port) === false) {
            throw new RuntimeException(
                "connect error:{$server}:{$port}"
            );
        }

        return clone $cache;
    }
}
