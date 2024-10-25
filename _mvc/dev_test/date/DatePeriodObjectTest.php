<?php

declare(strict_types=1);

namespace test\Concerto\data;

use PHPUnit\Framework\TestCase;
use test\Concerto\{
    ExpansionAssertionTrait,
    PrivateTestTrait,
};
use DatePeriod;
use Concerto\date\{
    DateInterface,
    DateIntervalInterface,
};
use Concerto\date\{
    DateIntervalObject,
    DateObject,
    DatePeriodObject,
};

class DatePeriodObjectTest extends TestCase
{
    use PrivateTestTrait;
    use ExpansionAssertionTrait;

    protected function setUp(): void
    {
        ini_set('date.timezone', 'Asia/Tokyo');
    }

    public function _constructProvider()
    {
        //end is DateObject option 0
        $start[0] = new DateObject('2021-05-01');
        $end[0] = new DateObject('2021-11-01');
        $interval[0] = new DateIntervalObject('P1M');
        $option[0] = 0;

        //end is DateObject option EXCLUDE_START_DATE
        $start[1] = new DateObject('2021-05-01');
        $end[1] = new DateObject('2021-11-01');
        $interval[1] = new DateIntervalObject('P1M');
        $option[1] = DatePeriod::EXCLUDE_START_DATE;

        //end is recurrence option 0
        $start[2] = new DateObject('2021-05-01');
        $end[2] = 9;
        $interval[2] = new DateIntervalObject('P1M');
        $option[2] = 0;

        //end is recurrence option EXCLUDE_START_DATE
        $start[1] = new DateObject('2021-05-01');
        $end[1] = 9;
        $interval[1] = new DateIntervalObject('P1M');
        $option[1] = DatePeriod::EXCLUDE_START_DATE;

        return array_map(
            function (
                $start,
                $interval,
                $end,
                $option,
            ) {
                return [
                    $start,
                    $interval,
                    $end,
                    $option,
                    new DatePeriod(
                        $start->toDateTimeImmutable(),
                        $interval->toDateInterval(),
                        is_int($end) ?
                            $end :
                            $end->toDateTimeImmutable(),
                        $option,
                    ),
                ];
            },
            $start,
            $interval,
            $end,
            $option,
        );
    }

    /**
    *   @test
    *   @dataProvider _constructProvider
    */
    public function _construct(
        DateInterface $start,
        DateIntervalInterface $interval,
        DateInterface|int $end_or_recurrences,
        int $options,
        DatePeriod $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = new DatePeriodObject(
            $start,
            $interval,
            $end_or_recurrences,
            $options,
        );

        $this->assertEquals(
            $expect,
            $obj->toDatePeriod(),
        );

        $this->assertEquals(
            $start,
            $obj->startDate(),
        );

        $this->assertEquals(
            $interval,
            $obj->interval(),
        );

        if (is_int($end_or_recurrences)) {
            $this->assertEquals(
                $end_or_recurrences,
                $obj->count(),
            );
        } else {
            $this->assertEquals(
                $end_or_recurrences,
                $obj->endDate(),
            );
        }
    }

    public function createFromDatePeriodProvider()
    {
        //end is DateObject option 0
        $start[0] = new DateObject('2021-05-01');
        $end[0] = new DateObject('2021-11-01');
        $interval[0] = new DateIntervalObject('P1M');
        $option[0] = 0;

        //end is DateObject option EXCLUDE_START_DATE
        $start[1] = new DateObject('2021-05-01');
        $end[1] = new DateObject('2021-11-01');
        $interval[1] = new DateIntervalObject('P1M');
        $option[1] = DatePeriod::EXCLUDE_START_DATE;

        //end is recurrence option 0
        $start[2] = new DateObject('2021-05-01');
        $end[2] = 9;
        $interval[2] = new DateIntervalObject('P1M');
        $option[2] = 0;

        //end is recurrence option EXCLUDE_START_DATE
        $start[1] = new DateObject('2021-05-01');
        $end[1] = 9;
        $interval[1] = new DateIntervalObject('P1M');
        $option[1] = DatePeriod::EXCLUDE_START_DATE;

        return array_map(
            function (
                $start,
                $interval,
                $end,
                $option,
            ) {
                return [
                    new DatePeriod(
                        $start->toDateTimeImmutable(),
                        $interval->toDateInterval(),
                        is_int($end) ?
                            $end :
                            $end->toDateTimeImmutable(),
                        $option,
                    ),
                    $option,
                    new DatePeriod(
                        $start->toDateTimeImmutable(),
                        $interval->toDateInterval(),
                        is_int($end) ?
                            $end :
                            $end->toDateTimeImmutable(),
                        $option,
                    ),
                ];
            },
            $start,
            $interval,
            $end,
            $option,
        );
    }

    /**
    *   @test
    *   @dataProvider createFromDatePeriodProvider
    */
    public function createFromDatePeriod(
        DatePeriod $period,
        int $options,
        DatePeriod $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = DatePeriodObject::createFromDatePeriod(
            $period,
            $options,
        );

        $this->assertEquals(
            $expect,
            $obj->toDatePeriod(),
        );
    }


    public function getIteratorProvider()
    {
        //end is DateObject option 0
        $start[0] = new DateObject('2021-05-01');
        $end[0] = new DateObject('2021-11-01');
        $interval[0] = new DateIntervalObject('P1M');
        $option[0] = 0;
        $expect[0] = [
            $start[0],
            ($start[0])->add(new DateIntervalObject('P1M')),
            ($start[0])->add(new DateIntervalObject('P2M')),
            ($start[0])->add(new DateIntervalObject('P3M')),
            ($start[0])->add(new DateIntervalObject('P4M')),
            ($start[0])->add(new DateIntervalObject('P5M')),
        ];

        //end is DateObject option EXCLUDE_START_DATE
        $start[1] = new DateObject('2021-05-01');
        $end[1] = new DateObject('2021-11-01');
        $interval[1] = new DateIntervalObject('P1M');
        $option[1] = DatePeriod::EXCLUDE_START_DATE;
        $expect[1] = [
            ($start[1])->add(new DateIntervalObject('P1M')),
            ($start[1])->add(new DateIntervalObject('P2M')),
            ($start[1])->add(new DateIntervalObject('P3M')),
            ($start[1])->add(new DateIntervalObject('P4M')),
            ($start[1])->add(new DateIntervalObject('P5M')),
        ];

        //end is recurrence option 0
        $start[2] = new DateObject('2021-05-01');
        $end[2] = 9;
        $interval[2] = new DateIntervalObject('P1M');
        $option[2] = 0;
        $expect[2] = [
            $start[2],
            ($start[2])->add(new DateIntervalObject('P1M')),
            ($start[2])->add(new DateIntervalObject('P2M')),
            ($start[2])->add(new DateIntervalObject('P3M')),
            ($start[2])->add(new DateIntervalObject('P4M')),
            ($start[2])->add(new DateIntervalObject('P5M')),
            ($start[2])->add(new DateIntervalObject('P6M')),
            ($start[2])->add(new DateIntervalObject('P7M')),
            ($start[2])->add(new DateIntervalObject('P8M')),
            ($start[2])->add(new DateIntervalObject('P9M')),
        ];

        //end is recurrence option EXCLUDE_START_DATE
        $start[3] = new DateObject('2021-05-01');
        $end[3] = 9;
        $interval[3] = new DateIntervalObject('P1M');
        $option[3] = DatePeriod::EXCLUDE_START_DATE;
        $expect[3] = [
            ($start[3])->add(new DateIntervalObject('P1M')),
            ($start[3])->add(new DateIntervalObject('P2M')),
            ($start[3])->add(new DateIntervalObject('P3M')),
            ($start[3])->add(new DateIntervalObject('P4M')),
            ($start[3])->add(new DateIntervalObject('P5M')),
            ($start[3])->add(new DateIntervalObject('P6M')),
            ($start[3])->add(new DateIntervalObject('P7M')),
            ($start[3])->add(new DateIntervalObject('P8M')),
            ($start[3])->add(new DateIntervalObject('P9M')),
        ];

        return array_map(
            function (
                $start,
                $interval,
                $end,
                $option,
                $expect
            ) {
                return [
                    $start,
                    $interval,
                    $end,
                    $option,
                    $expect,
                ];
            },
            $start,
            $interval,
            $end,
            $option,
            $expect,
        );
    }

    /**
    *   @test
    *   @dataProvider getIteratorProvider
    */
    public function getIterator(
        DateInterface $start,
        DateIntervalInterface $interval,
        DateInterface|int $end_or_recurrences,
        int $options,
        array $expects,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = new DatePeriodObject(
            $start,
            $interval,
            $end_or_recurrences,
            $options,
        );

        $i = 0;
        foreach ($obj as $date) {
            $this->assertEquals(
                $expects[$i],
                $date,
            );
            $i++;
        }

        $this->assertEquals(
            false,
            isset($expects[$i]),
        );
    }
}
