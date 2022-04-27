<?php

declare(strict_types=1);

namespace test\Concerto\win;

use test\Concerto\ConcertoTestCase;
use Concerto\win\DateTimeStringParser;

class DateTimeStringParserTest extends ConcertoTestCase
{
    public function parseExceptionProvider1()
    {
        return [
            ['20170403112233'],
            ['20170403112233.456'],
            ['20170403112233x1'],
        ];
    }

    /**
    *   @test
    *   @dataProvider parseExceptionProvider1
    */
    public function parseException1($data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $obj = DateTimeStringParser::parse($data);
    }

    public function parseProvider()
    {
        $dataset = [
            '20170403112233+540',
            '20170403112233.45678-65',
        ];

        $expects = [
            \DateTime::createFromFormat('YmdHisT', '20170403112233+0900'),
            \DateTime::createFromFormat('YmdHisT', '20170403112233-0105'),
        ];

        return array_map(
            function ($date, $expect) {
                return [$date, $expect];
            },
            $dataset,
            $expects
        );
    }

    /**
    *   @test
    *   @dataProvider parseProvider
    */
    public function parse($data, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $actual = DateTimeStringParser::parse($data);
        $this->assertEquals($expect, $actual);
    }
}
