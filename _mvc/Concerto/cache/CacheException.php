<?php

/**
*   Exception
*
*   @version 170220
*/

declare(strict_types=1);

namespace Concerto\cache;

use RuntimeException;
use Psr\Cache\CacheException as cx;
use Psr\SimpleCache\CacheException as scx;

class CacheException extends RuntimeException implements cx, scx
{
}
