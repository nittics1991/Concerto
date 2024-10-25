<?php

declare(strict_types=1);

namespace test\Concerto\ROOT;

use DateTimeImmutable;
use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\DateTimeUtil;

class DateTimeUtilTest extends ConcertoTestCase
{
    protected function setUp(): void
    {
        ini_set('date.timezone', 'Asia/Tokyo');
    }

    public static function getIntervalYYYYMMProvider()
    {
        return [
           //年内
           [
               '20210101',
               '20211201',
               [
                   '202101',
                   '202102',
                   '202103',
                   '202104',
                   '202105',
                   '202106',
                   '202107',
                   '202108',
                   '202109',
                   '202110',
                   '202111',
                   '202112',
               ],
           ],
           //年内月末
           [
               '20210131',
               '20210430',
               [
                   '202101',
                   '202102',
                   '202103',
                   '202104',
               ],
           ],
           //年内 start<>01 start > end
           [
               '20210115',
               '20210330',
               [
                   '202101',
                   '202102',
                   '202103',
               ],
           ],
           //同月
           [
               '20210203',
               '20210228',
               [
                   '202102',
               ],
           ],
           //年超
           [
               '20211203',
               '20220228',
               [
                   '202112',
                   '202201',
                   '202202',
               ],
           ],
           //start > end
           [
               '20210501',
               '20210228',
               [
                   '202102',
                   '202103',
                   '202104',
                   '202105',
               ],
           ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('getIntervalYYYYMMProvider')]
    public function getIntervalYYYYMM(
        string $start,
        string $end,
        array $expect,
    ) {
//      $this->markTestIncomplete();

        $this->assertEquals(
            $expect,
            DateTimeUtil::getIntervalYYYYMM(
                $start,
                $end,
            ),
        );
    }
}
