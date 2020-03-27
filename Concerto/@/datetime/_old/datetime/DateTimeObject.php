<?php

/**
*   DateTimeObject
*
*   @version 170303
*/

namespace Concerto\datetime;

use DateTimeImmutable;
use Concerto\datetime\DateTimeBase;

class DateTimeObject extends DateTimeBase
{
    /**
    *   {inherit}
    *
    **/
    public function toString()
    {
        return $this->format('Ymd His');
    }
}
