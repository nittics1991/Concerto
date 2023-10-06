<?php

/**
*   StandardDateIntervalTrait
*
*   @version 220226
*/

declare(strict_types=1);

namespace Concerto\date\implement;

use DateInterval;
use InvalidArgumentException;
use ReflectionClass;
use RuntimeException;
use Concerto\date\DateIntervalInterface;

trait StandardDateIntervalTrait
{
    /*
    *   @val DateInterval
    */
    protected DateInterval $interval;

    /*
    *   __construct
    *
    *   @param string $duration
    */
    public function __construct(
        string $duration
    ) {
        $this->interval = new DateInterval(
            $duration
        );
    }

    /*
    *   {inherit}
    */
    public static function createFromDateInterval(
        DateInterval $interval
    ): DateIntervalInterface {
        $double = new self('P0Y');
        $ref_class = new ReflectionClass($double);
        $ref_property = $ref_class->getProperty('interval');
        $ref_property->setValue($double, $interval);

        return $double;
    }

    /*
    *   {inherit}
    */
    public static function createFromDateString(
        string $datetime
    ): DateIntervalInterface {
        $interval = DateInterval::createFromDateString(
            $datetime,
        );

        if ($interval === false) {
            throw new InvalidArgumentException(
                "format error:{$datetime}",
            );
        }
        return new self(
            $interval->format(
                "P%yY%mM%dDT%hH%iM%sS",
            ),
        );
    }

    /*
    *   {inherit}
    */
    public function format(
        string $format
    ): string {
        return $this->interval->format($format);
    }

    /*
    *   {inherit}
    */
    public function year(): int
    {
        return (int)$this->interval->format('%r%y');
    }

    /*
    *   {inherit}
    */
    public function month(): int
    {
        return (int)$this->interval->format('%r%m');
    }

    /*
    *   {inherit}
    */
    public function day(): int
    {
        return (int)$this->interval->format('%r%d');
    }

    /*
    *   {inherit}
    */
    public function hour(): int
    {
        return (int)$this->interval->format('%r%h');
    }

    /*
    *   {inherit}
    */
    public function minute(): int
    {
        return (int)$this->interval->format('%r%i');
    }

    /*
    *   {inherit}
    */
    public function second(): int
    {
        return (int)$this->interval->format('%r%s');
    }

    /*
    *   {inherit}
    */
    public function milliSecond(): float
    {
        return (float)$this->interval->format('%r%f') / 1000;
    }

    /*
    *   {inherit}
    */
    public function microSecond(): float
    {
        return (float)$this->interval->format('%r%f');
    }

    /*
    *   {inherit}
    */
    public function days(): int
    {
        if ($this->interval->days === false) {
            throw new RuntimeException(
                "must be generated from diff/except()",
            );
        }
        return (int)$this->interval->format('%r%a');
    }

    /*
    *   {inherit}
    */
    public function toDateInterval(): DateInterval
    {
        return $this->interval;
    }
}
