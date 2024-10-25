<?php

declare(strict_types=1);

namespace test\Concerto\ROOT;

use DateInterval;
use DatePeriod;
use DateTime;
use DateTimeImmutable;
use test\Concerto\ConcertoTestCase;
use Concerto\FiscalYear;

class FiscalYear2 extends FiscalYear
{
    /**
    *   年度開始月
    *
    *   @var int
    */
    protected const HALF_START_MONTH = 10;

    /**
    *   上期format
    *
    *   @var string
    *   @see HALF_FROMAT|QUATER_FROMAT
    */
    protected const FIRST_HALF_FROMAT = 'Q_Y年度上期';

    /**
    *   下期format
    *
    *   @var string
    *   @see HALF_FROMAT|QUATER_FROMAT
    */
    protected const LAST_HALF_FROMAT = 'Q_Y年度下期';
}

class FiscalYearTest2 extends ConcertoTestCase
{
    protected function setUp(): void
    {
        ini_set('date.timezone', 'Asia/Tokyo');
    }

    public static function isFirstHalfCodeProvider()
    {
        return [
            [
                '2000K',
                true
            ],
            [
                '2000S',
                false
            ],
            [
                '2000k',
                false
            ],
            [
                '2000s',
                false
            ],
            [
                '999K',
                false
            ],
            [
                '99999K',
                false
            ],
            [
                2000,
                false
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider isFirstHalfCodeProvider
    */
    public function isFirstHalfCode(
        $kb_nendo,
        $expect,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            FiscalYear2::isFirstHalfCode($kb_nendo),
        );
    }

    public static function isLastHalfCodeProvider()
    {
        return [
            [
                '2000K',
                false
            ],
            [
                '2000S',
                true
            ],
            [
                '2000k',
                false
            ],
            [
                '2000s',
                false
            ],
            [
                '999K',
                false
            ],
            [
                '99999K',
                false
            ],
            [
                2000,
                false
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider isLastHalfCodeProvider
    */
    public function isLastHalfCode(
        $kb_nendo,
        $expect,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            FiscalYear2::isLastHalfCode($kb_nendo),
        );
    }

    public static function isHalfCodeProvider()
    {
        return [
            [
                '2000K',
                true
            ],
            [
                '2000S',
                true
            ],
            [
                '2000k',
                false
            ],
            [
                '2000s',
                false
            ],
            [
                '999K',
                false
            ],
            [
                '99999K',
                false
            ],
            [
                2000,
                false
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider isHalfCodeProvider
    */
    public function isHalfCode(
        $kb_nendo,
        $expect,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            FiscalYear2::isHalfCode($kb_nendo),
        );
    }

    public static function inFirstHalfProvider()
    {
        return [
            [
                new DateTimeImmutable('2020-4-1 today'),
                false,
            ],
            [
                new DateTime('2020-4-1 today'),
                false,
            ],
            [
                new DateTimeImmutable('2020-9-30 23:59:59'),
                false,
            ],
            [
                new DateTimeImmutable('2020-10-1 today'),
                true,
            ],
            [
                new DateTimeImmutable('2020-3-31 23:59:59'),
                true,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider inFirstHalfProvider
    */
    public function inFirstHalf(
        $datetime,
        $expect,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            FiscalYear2::inFirstHalf($datetime),
        );
    }

    public static function inLastHalfProvider()
    {
        return [
            [
                new DateTimeImmutable('2020-10-1 today'),
                false,
            ],
            [
                new DateTime('2020-10-1 today'),
                false,
            ],
            [
                new DateTimeImmutable('2021-3-31 23:59:59'),
                false,
            ],
            [
                new DateTimeImmutable('2021-4-1 today'),
                true,
            ],
            [
                new DateTimeImmutable('2020-9-30 23:59:59'),
                true,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider inLastHalfProvider
    */
    public function inLastHalf(
        $datetime,
        $expect,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            FiscalYear2::inLastHalf($datetime),
        );
    }

    public static function parseCodeProvider()
    {
        return [
            [
                2000,
                [],
            ],
            [
                '2000K',
                ['year' => '2000', 'code' => 'K',],
            ],
            [
                '2000S',
                ['year' => '2000', 'code' => 'S',],
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider parseCodeProvider
    */
    public function parseCode(
        $kb_nendo,
        $expect,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            FiscalYear2::parseCode($kb_nendo),
        );
    }

    public static function datetimeToCodeProvider()
    {
        return [
            [
                new DateTimeImmutable('2020-4-1 today'),
                '2019S',
            ],
            [
                new DateTime('2020-4-1 today'),
                '2019S',
            ],
            [
                new DateTimeImmutable('2020-9-30 23:59:59'),
                '2019S',
            ],
            [
                new DateTimeImmutable('2020-10-1 today'),
                '2020K',
            ],
            [
                new DateTimeImmutable('2021-3-31 23:59:59'),
                '2020K',
            ],
            [
                new DateTimeImmutable('2020-3-31 23:59:59'),
                '2019K',
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider datetimeToCodeProvider
    */
    public function datetimeToCode(
        $datetime,
        $expect,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            FiscalYear2::datetimeToCode($datetime),
        );
    }

    public static function codeToDatetimeProvider()
    {
        return [
            [
                '2020K',
                new DateTimeImmutable('2020-10-1 00:00:00'),
            ],
            [
                '2020S',
                new DateTimeImmutable('2021-04-1 00:00:00'),
            ],
            [
                '2020',
                null,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider codeToDatetimeProvider
    */
    public function codeToDatetime(
        $kb_nendo,
        $expect,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            FiscalYear2::codeToDatetime($kb_nendo),
        );
    }

    public static function datetimeInPeriodProvider()
    {
        return [
            [
                new DateTimeImmutable('2020-4-1 today'),
                new DatePeriod(
                    new DateTimeImmutable('2020-4-1'),
                    new DateInterval('P1M'),
                    5
                ),
            ],
            [
                new DateTimeImmutable('2020-10-1 today'),
                new DatePeriod(
                    new DateTimeImmutable('2020-10-1 today'),
                    new DateInterval('P1M'),
                    5
                ),
            ],
            [
                new DateTimeImmutable('2020-9-30 23:59:59'),
                new DatePeriod(
                    new DateTimeImmutable('2020-4-1 today'),
                    new DateInterval('P1M'),
                    5
                ),
            ],
            [
                new DateTimeImmutable('2021-3-31 23:59:59'),
                new DatePeriod(
                    new DateTimeImmutable('2020-10-1 today'),
                    new DateInterval('P1M'),
                    5
                ),
            ],
            [
                new DateTimeImmutable('2020-3-31 23:59:59'),
                new DatePeriod(
                    new DateTimeImmutable('2019-10-1 today'),
                    new DateInterval('P1M'),
                    5
                ),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider datetimeInPeriodProvider
    */
    public function datetimeInPeriod(
        $datetime,
        $expect,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            FiscalYear2::datetimeInPeriod($datetime),
        );
    }

    public static function codeInPeriodProvider()
    {
        return [
            [
                '2000K',
                new DatePeriod(
                    new DateTimeImmutable('2000-10-1 today'),
                    new DateInterval('P1M'),
                    5,
                ),
            ],
            [
                '2000S',
                new DatePeriod(
                    new DateTimeImmutable('2001-4-1 today'),
                    new DateInterval('P1M'),
                    5,
                ),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider codeInPeriodProvider
    */
    public function codeInPeriod(
        $kb_nendo,
        $expect,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            FiscalYear2::codeInPeriod($kb_nendo),
        );
    }

    public static function formattedPeriodProvider()
    {
        return [
            [
                'Ymd',
                '2000K',
                [
                    '20001001',
                    '20001101',
                    '20001201',
                    '20010101',
                    '20010201',
                    '20010301',
                ],
            ],
            [
                '2000K',
                null,
                [
                    '2000K',
                    '2000K',
                    '2000K',
                    '2000K',
                    '2000K',
                    '2000K',
                ],
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider formattedPeriodProvider
    */
    public function formattedPeriod(
        $format,
        $kb_nendo,
        $expect,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            FiscalYear2::formattedPeriod($format, $kb_nendo),
        );
    }

    /**
    *   @test
    */
    public function testSuccessGetPresentNendo()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $today = getdate();

        if ($today['mon'] >= 10 && $today['mon'] <= 12) {
            $expect = $today['year'] . 'K';
        } elseif ($today['mon'] >= 4 && $today['mon'] <= 9) {
            $expect = ($today['year'] - 1) . 'S';
        } else {
            $expect = ($today['year'] - 1) . 'K';
        }

        $this->assertEquals($expect, FiscalYear2::getPresentNendo());
    }

    /**
    *   指定年度の次年度
    *
    */
    public function providerSuccessGetNextNendo()
    {
        return [
            ['2015K', '2015S'],
            ['2015S', '2016K'],
            ['1S', false],
            ['2015Z', false],
            ['2015', false]
        ];
    }

    /**
    *   @test
    *   @dataProvider providerSuccessGetNextNendo
    */
    public function testSuccessGetNextNendo($argv, $result)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($result, FiscalYear2::getNextNendo($argv));
    }

    /**
    *   指定年度の前年度
    */
    public function providerSuccessGetPreviousNendo()
    {
        return [
            ['2015S', '2015K'],
            ['2015K', '2014S'],
            ['1S', false],
            ['2015Z', false],
            ['2015', false]
        ];
    }

    /**
    *   @test
    *   @dataProvider providerSuccessGetPreviousNendo
    */
    public function testSuccessGetPreviousNendo($argv, $result)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($result, FiscalYear2::getPreviousNendo($argv));
    }

    /**
    *   @test
    */
    public function addNendo()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals('2015K', FiscalYear2::addNendo('2015K', 0));

        $this->assertEquals('2015S', FiscalYear2::addNendo('2015K', 1));
        $this->assertEquals('2016K', FiscalYear2::addNendo('2015K', 2));
        $this->assertEquals('2016S', FiscalYear2::addNendo('2015K', 3));

        $this->assertEquals('2016K', FiscalYear2::addNendo('2015S', 1));
        $this->assertEquals('2016S', FiscalYear2::addNendo('2015S', 2));
        $this->assertEquals('2017K', FiscalYear2::addNendo('2015S', 3));

        $this->assertEquals('2014S', FiscalYear2::addNendo('2015K', -1));
        $this->assertEquals('2014K', FiscalYear2::addNendo('2015K', -2));
        $this->assertEquals('2013S', FiscalYear2::addNendo('2015K', -3));

        $this->assertEquals('2015K', FiscalYear2::addNendo('2015S', -1));
        $this->assertEquals('2014S', FiscalYear2::addNendo('2015S', -2));
        $this->assertEquals('2014K', FiscalYear2::addNendo('2015S', -3));
    }

    /**
    *
    */
    public function providerSuccessNendoCodeToZn()
    {
        return [
            ['2015S', 'Ｓ＿２０１５年度下期'],
            ['2015K', 'Ｋ＿２０１５年度上期'],
            ['1S', false],
            ['2015Z', false],
            ['2015', false]
        ];
    }

    /**
    *   @test
    *   @dataProvider providerSuccessNendoCodeToZn
    */
    public function testSuccessNendoCodeToZn($argv, $result)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($result, FiscalYear2::nendoCodeToZn($argv));
    }

    /**
    *
    */
    public function providerSuccessGetNendoyyyymm()
    {
        return [
            [
                '2015K',
                ['201510', '201511', '201512', '201601', '201602', '201603' ]
            ],
            [
                '2015S',
                ['201604', '201605', '201606', '201607', '201608', '201609' ]
            ],
            [
                '1K',
                []
            ],
            [
                '2015Z',
                []
            ],
            [
                '2015',
                []
            ],
        ];
    }

    /**
    *   @dataProvider providerSuccessGetNendoyyyymm
    *
    */
    public function testSuccessGetNendoyyyymm($argv, $result)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($result, FiscalYear2::getNendoyyyymm($argv));
    }

    /**
    *
    */
    public function providerSuccessGetNendomm()
    {
        return [
            ['2015K', ['10', '11', '12', '01', '02', '03' ]],
            ['2015S', ['04', '05', '06', '07', '08', '09' ]],
            ['1K', []],
            ['2015Z', []],
            ['2015', []]
        ];
    }

    /**
    *   @dataProvider providerSuccessGetNendomm
    *
    */
    public function testSuccessGetNendomm($argv, $result)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($result, FiscalYear2::getNendomm($argv));
    }

    /**
    *
    */
    public function providerSuccessGetyyyymmToNendo()
    {
        return [
            ['201504', '2014S'],
            ['201505', '2014S'],
            ['201506', '2014S'],
            ['201507', '2014S'],
            ['201508', '2014S'],
            ['201509', '2014S'],
            ['201510', '2015K'],
            ['201511', '2015K'],
            ['201512', '2015K'],
            ['201601', '2015K'],
            ['201602', '2015K'],
            ['201603', '2015K'],
            ['20164', false],
            ['201600', false],
            ['201613', false]
        ];
    }

    /**
    *   @dataProvider providerSuccessGetyyyymmToNendo
    *
    */
    public function testSuccessGetyyyymmToNendo($argv, $result)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($result, FiscalYear2::getyyyymmToNendo($argv));
    }

    /**
    *
    */
    public function providerSuccessGetNendoPeriod()
    {
        return [
            ['2015K', ['201510', '201603']],
            ['2015S', ['201604', '201609']],
            ['5K', []],
            ['2015Z', []],
            ['2015', []]
        ];
    }

    /**
    *   @dataProvider providerSuccessGetNendoPeriod
    *   @group only
    */
    public function testSuccessGetNendoPeriod($argv, $result)
    {
     // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($result, FiscalYear2::getNendoPeriod($argv));
    }

    public static function getNendoPeriodCollectionProvider()
    {
        return [
            [
                '2013S',
                '2016K',
                ['2013S', '2014K', '2014S', '2015K', '2015S', '2016K']
            ],

            [
                '2016K',
                '2013S',
                ['2016K', '2015S', '2015K', '2014S', '2014K', '2013S']
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider getNendoPeriodCollectionProvider
    */
    public function getNendoPeriodCollection($start, $end, $expect)
    {
        $actual = FiscalYear2::getNendoPeriodCollection($start, $end);
        reset($expect);

        foreach ($actual as $list) {
            $this->assertEquals(current($expect), $list['kb_nendo']);
            $this->assertEquals(FiscalYear2::nendoCodeToZn(current($expect)), $list['nm_nendo']);
            next($expect);
        }
    }

    public static function diffProvider()
    {
        return [
            ['2016K', '2016K', 0],
            ['2016S', '2016S', 0],

            ['2016K', '2016S', 1],
            ['2016K', '2017K', 2],
            ['2016K', '2017S', 3],
            ['2016K', '2018K', 4],
            ['2016K', '2018S', 5],
            ['2016K', '2019K', 6],

            ['2016K', '2015S', -1],
            ['2016K', '2015K', -2],
            ['2016K', '2014S', -3],
            ['2016K', '2014K', -4],
            ['2016K', '2013S', -5],
            ['2016K', '2013K', -6],

            ['2016S', '2017K', 1],
            ['2016S', '2017S', 2],
            ['2016S', '2018K', 3],
            ['2016S', '2018S', 4],
            ['2016S', '2019K', 5],
            ['2016S', '2019S', 6],

            ['2016S', '2016K', -1],
            ['2016S', '2015S', -2],
            ['2016S', '2015K', -3],
            ['2016S', '2014S', -4],
            ['2016S', '2014K', -5],
            ['2016S', '2013S', -6],
        ];
    }

    /**
    *   @test
    *   @dataProvider diffProvider
    */
    public function testDiff($base, $target, $expect)
    {
        $this->assertEquals(FiscalYear2::diff($base, $target), $expect);
    }
}
