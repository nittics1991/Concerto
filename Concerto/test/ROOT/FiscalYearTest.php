<?php

declare(strict_types=1);

namespace Concerto\test\ROOT;

use Concerto\test\ConcertoTestCase;
use Concerto\FiscalYear;

class FiscalYearTest extends ConcertoTestCase
{
    /**
    *   現在年度
    *
    */
    public function testSuccessGetPresentNendo()
    {
//      $this->markTestIncomplete();

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
    public function providerSuccessGetNextNendo()
    {
        return array(
            array('2015K', '2015S'),
            array('2015S', '2016K'),
            array('1S', false),
            array('2015Z', false),
            array('2015', false)
        );
    }

    /**
    *   @dataProvider providerSuccessGetNextNendo
    *
    */
    public function testSuccessGetNextNendo($argv, $result)
    {
//      $this->markTestIncomplete();

        $this->assertEquals($result, FiscalYear::getNextNendo($argv));
    }

    /**
    *   指定年度の前年度
    *
    */
    public function providerSuccessGetPreviousNendo()
    {
        return array(
            array('2015S', '2015K'),
            array('2015K', '2014S'),
            array('1S', false),
            array('2015Z', false),
            array('2015', false)
        );
    }

    /**
    *   @dataProvider providerSuccessGetPreviousNendo
    *
    */
    public function testSuccessGetPreviousNendo($argv, $result)
    {
//      $this->markTestIncomplete();

        $this->assertEquals($result, FiscalYear::getPreviousNendo($argv));
    }

    /**
    *   年度記号＝＞年度全角
    *
    */
    public function providerSuccessNendoCodeToZn()
    {
        return array(
            array('2015S', '２０１５年下期'),
            array('2015K', '２０１５年上期'),
            array('1S', false),
            array('2015Z', false),
            array('2015', false)
        );
    }

    /**
    *   @dataProvider providerSuccessNendoCodeToZn
    *
    */
    public function testSuccessNendoCodeToZn($argv, $result)
    {
//      $this->markTestIncomplete();

        $this->assertEquals($result, FiscalYear::nendoCodeToZn($argv));
    }

    /**
    *   年度全角＝＞年度記号
    *
    */
    public function providerSuccessNendoZnToCode()
    {
        return array(
            array('２０１５年下期', '2015S'),
            array('２０１５年上期', '2015K'),
            array('２０１５年下', false),
            array('２０１５下期', false),
            array('2015K', false)
        );
    }

    /**
    *   @dataProvider providerSuccessNendoZnToCode
    *
    */
    public function testSuccessNendoZnToCode($argv, $result)
    {
//      $this->markTestIncomplete();

        $this->assertEquals($result, FiscalYear::nendoZnToCode($argv));
    }

    /**
    *   年度内年月
    *
    */
    public function providerSuccessGetNendoyyyymm()
    {
        return array(
            array('2015K', array('201504', '201505', '201506', '201507', '201508', '201509' )),
            array('2015S', array('201510', '201511', '201512', '201601', '201602', '201603' )),
            array('1K', array()),
            array('2015Z', array()),
            array('2015', array())

        );
    }

    /**
    *   @dataProvider providerSuccessGetNendoyyyymm
    *
    */
    public function testSuccessGetNendoyyyymm($argv, $result)
    {
//      $this->markTestIncomplete();

        $this->assertEquals($result, FiscalYear::getNendoyyyymm($argv));
    }

    /**
    *   年度内年月
    *
    */
    public function providerSuccessGetNendomm()
    {
        return array(
            array('2015K', array('04', '05', '06', '07', '08', '09' )),
            array('2015S', array('10', '11', '12', '01', '02', '03' )),
            array('1K', array()),
            array('2015Z', array()),
            array('2015', array())

        );
    }

    /**
    *   @dataProvider providerSuccessGetNendomm
    *
    */
    public function testSuccessGetNendomm($argv, $result)
    {
//      $this->markTestIncomplete();

        $this->assertEquals($result, FiscalYear::getNendomm($argv));
    }

    /**
    *   年月=>年度
    *
    */
    public function providerSuccessGetyyyymmToNendo()
    {
        return array(
            array('201504', '2015K'),
            array('201505', '2015K'),
            array('201506', '2015K'),
            array('201507', '2015K'),
            array('201508', '2015K'),
            array('201509', '2015K'),
            array('201510', '2015S'),
            array('201511', '2015S'),
            array('201512', '2015S'),
            array('201601', '2015S'),
            array('201602', '2015S'),
            array('201603', '2015S'),
            array('20164', false),
            array('201600', false),
            array('201613', false)
        );
    }

    /**
    *   @dataProvider providerSuccessGetyyyymmToNendo
    *
    */
    public function testSuccessGetyyyymmToNendo($argv, $result)
    {
//      $this->markTestIncomplete();

        $this->assertEquals($result, FiscalYear::getyyyymmToNendo($argv));
    }

    /**
    *   年度=>開始年月・終了年月
    *
    */
    public function providerSuccessGetNendoPeriod()
    {
        return array(
            array('2015K', array('201504', '201509')),
            array('2015S', array('201510', '201603')),
            array('5K', array()),
            array('2015Z', array()),
            array('2015', array())
        );
    }

    /**
    *   @dataProvider providerSuccessGetNendoPeriod
    *
    */
    public function testSuccessGetNendoPeriod($argv, $result)
    {
//      $this->markTestIncomplete();

        $this->assertEquals($result, FiscalYear::getNendoPeriod($argv));
    }

    /**
    *
    *   @test
    */
    public function addNendo()
    {
//      $this->markTestIncomplete();

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

    public function getNendoPeriodCollectionProvider()
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
        $actual = FiscalYear::getNendoPeriodCollection($start, $end);
        reset($expect);

        foreach ($actual as $list) {
            $this->assertEquals(current($expect), $list['kb_nendo']);
            $this->assertEquals(FiscalYear::nendoCodeToZn(current($expect)), $list['nm_nendo']);
            next($expect);
        }
    }

    public function diffProvider()
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
        $this->assertEquals(FiscalYear::diff($base, $target), $expect);
    }
}
