<?php

/**
*   StandardDateTimeZoneTrait
*
*   @version 220226
*/

declare(strict_types=1);

namespace Concerto\date\implement;

use DateTimeZone;
use DateTimeImmutable;
use Concerto\date\DateTimeZoneInterface;

trait StandardDateTimeZoneTrait
{
    /*
    *   @val DateTimeZone
    */
    protected DateTimeZone $timezone;

    /*
    *   __construct
    *
    *   @param string $timezone
    */
    public function __construct(
        ?string $timezone = null,
    ) {
        $this->timezone = new DateTimeZone(
            $timezone ?? date_default_timezone_get(),
        );
    }

    /*
    *   {inherit}
    */
    public function offsetTime(): int
    {
        return $this->timezone->getOffset(
            new DateTimeImmutable(
                'now',
                new DateTimeZone('UTC')
            )
        );
    }

    /*
    *   {inherit}
    */
    public function toDateTimeZone(): DateTimeZone
    {
        return new DateTimeZone(
            $this->timezone->getName(),
        );
    }
    /*
    *   {inherit}
    */
    public function getName(): string
    {
        return $this->timezone->getName();
    }
}
