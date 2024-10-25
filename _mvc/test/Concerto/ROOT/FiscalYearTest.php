<?php

declare(strict_types=1);

namespace test\Concerto\ROOT;

use DateInterval;
use DatePeriod;
use DateTime;
use DateTimeImmutable;
use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\FiscalYear;

class FiscalYearTest extends ConcertoTestCase
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
    */
    #[Test]
    #[DataProvider('isFirstHalfCodeProvider')]
    public function isFirstHalfCode(
        $kb_nendo,
        $expect,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            FiscalYear::isFirstHalfCode($kb_nendo),
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
    */
    #[Test]
    #[DataProvider('isLastHalfCodeProvider')]
    public function isLastHalfCode(
        $kb_nendo,
        $expect,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            FiscalYear::isLastHalfCode($kb_nendo),
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
    */
    #[Test]
    #[DataProvider('isHalfCodeProvider')]
    public function isHalfCode(
        $kb_nendo,
        $expect,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            FiscalYear::isHalfCode($kb_nendo),
        );
    }

    public static function inFirstHalfProvider()
    {
        return [
            [
                new DateTimeImmutable('2020-4-1 today'),
                true,
            ],
            [
                new DateTime('2020-4-1 today'),
                true,
            ],
            [
                new DateTimeImmutable('2020-9-30 23:59:59'),
                true,
            ],
            [
                new DateTimeImmutable('2020-10-1 today'),
                false,
            ],
            [
                new DateTimeImmutable('2020-3-31 23:59:59'),
                false,
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('inFirstHalfProvider')]
    public function inFirstHalf(
        $datetime,
        $expect,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            FiscalYear::inFirstHalf($datetime),
        );
    }

    public static function inLastHalfProvider()
    {
        return [
            [
                new DateTimeImmutable('2020-10-1 today'),
                true,
            ],
            [
                new DateTime('2020-10-1 today'),
                true,
            ],
            [
                new DateTimeImmutable('2021-3-31 23:59:59'),
                true,
            ],
            [
                new DateTimeImmutable('2021-4-1 today'),
                false,
            ],
            [
                new DateTimeImmutable('2020-9-30 23:59:59'),
                false,
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('inLastHalfProvider')]
    public function inLastHalf(
        $datetime,
        $expect,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            FiscalYear::inLastHalf($datetime),
        );
    }

    public static function parseCodeProvider()
    {
        return [
            [
                '2000',
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
    */
    #[Test]
    #[DataProvider('parseCodeProvider')]
    public function parseCode(
        $kb_nendo,
        $expect,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            FiscalYear::parseCode($kb_nendo),
        );
    }

    public static function datetimeToCodeProvider()
    {
        return [
            [
                new DateTimeImmutable('2020-4-1 today'),
                '2020K',
            ],
            [
                new DateTime('2020-4-1 today'),
                '2020K',
            ],
            [
                new DateTimeImmutable('2020-9-30 23:59:59'),
                '2020K',
            ],
            [
                new DateTimeImmutable('2020-10-1 today'),
                '2020S',
            ],
            [
                new DateTimeImmutable('2021-3-31 23:59:59'),
                '2020S',
            ],
            [
                new DateTimeImmutable('2020-3-31 23:59:59'),
                '2019S',
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('datetimeToCodeProvider')]
    public function datetimeToCode(
        $datetime,
        $expect,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            FiscalYear::datetimeToCode($datetime),
        );
    }

    public static function codeToDatetimeProvider()
    {
        return [
            [
                '2020K',
                new DateTimeImmutable('2020-4-1 00:00:00'),
            ],
            [
                '2020S',
                new DateTimeImmutable('2020-10-1 00:00:00'),
            ],
            [
                '2020',
                null,
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('codeToDatetimeProvider')]
    public function codeToDatetime(
        $kb_nendo,
        $expect,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            FiscalYear::codeToDatetime($kb_nendo),
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
    */
    #[Test]
    #[DataProvider('datetimeInPeriodProvider')]
    public function datetimeInPeriod(
        $datetime,
        $expect,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            FiscalYear::datetimeInPeriod($datetime),
        );
    }

    public static function codeInPeriodProvider()
    {
        return [
            [
                '2000K',
                new DatePeriod(
                    new DateTimeImmutable('2000-4-1 today'),
                    new DateInterval('P1M'),
                    5,
                ),
            ],
            [
                '2000S',
                new DatePeriod(
                    new DateTimeImmutable('2000-10-1 today'),
                    new DateInterval('P1M'),
                    5,
                ),
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('codeInPeriodProvider')]
    public function codeInPeriod(
        $kb_nendo,
        $expect,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            FiscalYear::codeInPeriod($kb_nendo),
        );
    }

    public static function formattedPeriodProvider()
    {
        return [
            [
                'Ymd',
                '2000K',
                [
                    '20000401',
                    '20000501',
                    '20000601',
                    '20000701',
                    '20000801',
                    '20000901',
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
    */
    #[Test]
    #[DataProvider('formattedPeriodProvider')]
    public function formattedPeriod(
        $format,
        $kb_nendo,
        $expect,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            FiscalYear::formattedPeriod($format, $kb_nendo),
        );
    }

    /**
    */
    #[Test]
    public function testSuccessGetPresentNendo()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $today = getdate();

        if ($today['mon'] >= 4 && $today['mon'] <= 9) {
            $expect = $today['year'] . 'K';
        } elseif ($today['mon'] >= 10 && $today['mon'] <= 12) {
            $expect = $today['year'] . 'S';
        } else {
            $expect = ($today['year'] - 1) . 'S';
        }

        $this->assertEquals($expect, FiscalYear::getPresentNendo());
    }

    /**
    *   指定年度の次年度
    *
    */
    public static function providerSuccessGetNextNendo()
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
    */
    #[Test]
    #[DataProvider('providerSuccessGetNextNendo')]
    public function testSuccessGetNextNendo($argv, $result)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($result, FiscalYear::getNextNendo($argv));
    }

    /**
    *   指定年度の前年度
    */
    public static function providerSuccessGetPreviousNendo()
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
    */
    #[Test]
    #[DataProvider('providerSuccessGetPreviousNendo')]
    public function testSuccessGetPreviousNendo($argv, $result)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($result, FiscalYear::getPreviousNendo($argv));
    }

    /**
    */
    #[Test]
    public function addNendo()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals('2015K', FiscalYear::addNendo('2015K', 0));

        $this->assertEquals('2015S', FiscalYear::addNendo('2015K', 1));
        $this->assertEquals('2016K', FiscalYear::addNendo('2015K', 2));
        $this->assertEquals('2016S', FiscalYear::addNendo('2015K', 3));

        $this->assertEquals('2016K', FiscalYear::addNendo('2015S', 1));
        $this->assertEquals('2016S', FiscalYear::addNendo('2015S', 2));
        $this->assertEquals('2017K', FiscalYear::addNendo('2015S', 3));

        $this->assertEquals('2014S', FiscalYear::addNendo('2015K', -1));
        $this->assertEquals('2014K', FiscalYear::addNendo('2015K', -2));
        $this->assertEquals('2013S', FiscalYear::addNendo('2015K', -3));

        $this->assertEquals('2015K', FiscalYear::addNendo('2015S', -1));
        $this->assertEquals('2014S', FiscalYear::addNendo('2015S', -2));
        $this->assertEquals('2014K', FiscalYear::addNendo('2015S', -3));
    }

    /**
    *
    */
    public static function providerSuccessNendoCodeToZn()
    {
        return [
            ['2015S', '２０１５年下期'],
            ['2015K', '２０１５年上期'],
            ['1S', false],
            ['2015Z', false],
            ['2015', false]
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('providerSuccessNendoCodeToZn')]
    public function testSuccessNendoCodeToZn($argv, $result)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($result, FiscalYear::nendoCodeToZn($argv));
    }

    /**
    *
    */
    public static function providerSuccessGetNendoyyyymm()
    {
        return [
            [
                '2015K',
                ['201504', '201505', '201506', '201507', '201508', '201509' ]
            ],
            [
                '2015S',
                ['201510', '201511', '201512', '201601', '201602', '201603' ]
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
    *
    */
    #[DataProvider('providerSuccessGetNendoyyyymm')]
    public function testSuccessGetNendoyyyymm($argv, $result)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($result, FiscalYear::getNendoyyyymm($argv));
    }

    /**
    *
    */
    public static function providerSuccessGetNendomm()
    {
        return [
            ['2015K', ['04', '05', '06', '07', '08', '09' ]],
            ['2015S', ['10', '11', '12', '01', '02', '03' ]],
            ['1K', []],
            ['2015Z', []],
            ['2015', []]
        ];
    }

    /**
    *
    */
    #[DataProvider('providerSuccessGetNendomm')]
    public function testSuccessGetNendomm($argv, $result)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($result, FiscalYear::getNendomm($argv));
    }

    /**
    *
    */
    public static function providerSuccessGetyyyymmToNendo()
    {
        return [
            ['201504', '2015K'],
            ['201505', '2015K'],
            ['201506', '2015K'],
            ['201507', '2015K'],
            ['201508', '2015K'],
            ['201509', '2015K'],
            ['201510', '2015S'],
            ['201511', '2015S'],
            ['201512', '2015S'],
            ['201601', '2015S'],
            ['201602', '2015S'],
            ['201603', '2015S'],
            ['20164', false],
            ['201600', false],
            ['201613', false]
        ];
    }

    /**
    *
    */
    #[DataProvider('providerSuccessGetyyyymmToNendo')]
    public function testSuccessGetyyyymmToNendo($argv, $result)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($result, FiscalYear::getyyyymmToNendo($argv));
    }

    /**
    *
    */
    public static function providerSuccessGetNendoPeriod()
    {
        return [
            ['2015K', ['201504', '201509']],
            ['2015S', ['201510', '201603']],
            ['5K', []],
            ['2015Z', []],
            ['2015', []]
        ];
    }

    /**
    *
    */
    #[DataProvider('providerSuccessGetNendoPeriod')]
    public function testSuccessGetNendoPeriod($argv, $result)
    {
     // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($result, FiscalYear::getNendoPeriod($argv));
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
    */
    #[Test]
    #[DataProvider('getNendoPeriodCollectionProvider')]
    public function getNendoPeriodCollection($start, $end, $expect)
    {
        $actual = FiscalYear::getNendoPeriodCollection($start, $end);
        reset($expect);

        foreach ($actual as $list) {
            $this->assertEquals(current($expect), $list['kb_nendo']);
            $this->assertEquals(FiscalYear::nendoCodeToZn(current($expect)), $list['nm_nendo']);
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
    */
    #[Test]
    #[DataProvider('diffProvider')]
    public function testDiff($base, $target, $expect)
    {
        $this->assertEquals(FiscalYear::diff($base, $target), $expect);
    }
}
