<?php

/**
*   StandardDatePeriodTrait
*
*   @version 220226
*/

declare(strict_types=1);

namespace Concerto\date\implement;

use DatePeriod;
use Traversable;
use Concerto\date\{
    DateInterface,
    DateIntervalInterface,
    DatePeriodInterface,
};
use Concerto\date\{
    DateIntervalObject,
    DateObject,
};

trait StandardDatePeriodTrait
{
    /*
    *   @val DatePeriod
    */
    protected DatePeriod $period;

    /*
    *   @val options
    */
    protected int $options;

    /*
    *   __construct
    *
    *   @param DateInterface $start
    *   @param DateIntervalInterface $interval
    *   @param DateInterface|int $end_or_recurrences
    *   @param int $options
    */
    public function __construct(
        DateInterface $start,
        DateIntervalInterface $interval,
        DateInterface|int $end_or_recurrences,
        int $options = 0
    ) {
        $this->period = new DatePeriod(
            $start->toDateTimeImmutable(),
            $interval->toDateInterval(),
            is_int($end_or_recurrences) ?
                $end_or_recurrences :
                $end_or_recurrences->toDateTimeImmutable(),
            $options,
        );
        $this->options = $options;
    }

    /*
    *   {inherid}
    */
    public static function createFromDatePeriod(
        DatePeriod $period,
        int $options = 0,
    ): DatePeriodInterface {
        return new self(
            DateObject::createFromInterface(
                $period->getStartDate(),
            ),
            DateIntervalObject::createFromDateInterval(
                $period->getDateInterval(),
            ),
            $period->getEndDate() ?
                (DateObject::createFromInterface(
                    $period->getEndDate(),
                )) :
                (
                    $options === DatePeriod::EXCLUDE_START_DATE ?
                        $period->recurrences :
                        $period->recurrences - 1
                ),
            $options,
        );
    }

    /*
    *   {inherid}
    */
    public function getIterator(): Traversable
    {
        foreach ($this->period as $datetime) {
            yield DateObject::createFromInterface(
                $datetime,
            );
        }
    }

    /*
    *   {inherid}
    */
    public function count(): int
    {
        return (int)($this->period->getRecurrences());
    }

    /*
    *   {inherid}
    */
    public function toDatePeriod(): DatePeriod
    {
        return $this->period;
    }

    /*
    *   {inherid}
    */
    public function startDate(): DateInterface
    {
        return DateObject::createFromInterface(
            $this->period->getStartDate(),
        );
    }

    /*
    *   {inherid}
    */
    public function interval(): DateIntervalInterface
    {
        return DateIntervalObject::createFromDateInterval(
            $this->period->getDateInterval(),
        );
    }

    /*
    *   {inherid}
    */
    public function endDate(): DateInterface
    {
        return DateObject::createFromInterface(
            $this->period->getEndDate(),
        );
    }
}
