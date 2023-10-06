<?php

/**
*   Exception
*
*   @version 170220
*/

declare(strict_types=1);

namespace Concerto\cache;

use InvalidArgumentException as spl;
use Psr\Cache\InvalidArgumentException as ix;
use Psr\SimpleCache\InvalidArgumentException as six;

class InvalidArgumentException extends spl implements ix, six
{
}
