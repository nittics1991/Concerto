<?php

/**
*   DateObject
*
*   @version 220226
*/

declare(strict_types=1);

namespace Concerto\date;

use Concerto\date\DateInterface;
use Concerto\date\implement\StandardDateTrait;

class DateObject implements DateInterface
{
    use StandardDateTrait;
}
