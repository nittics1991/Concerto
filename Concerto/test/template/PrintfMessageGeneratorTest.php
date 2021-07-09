<?php

declare(strict_types=1);

namespace Concerto\test\template;

use Concerto\test\ConcertoTestCase;
use Concerto\template\PrintfMessageGenerator;

class PrintfMessageGeneratorTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function first()
    {
//      $this->markTestIncomplete();

        $obj = new PrintfMessageGenerator();
        $this->assertEquals('', $obj->generate());

        $msg = 'only string';
        $obj = new PrintfMessageGenerator($msg);
        $this->assertEquals($msg, $obj->generate());

        $msg = 'example number=%d, string %s';
        $actual = 'example number=123, string STRING';
        $obj = new PrintfMessageGenerator($msg);
        $this->assertEquals($actual, $obj->generate([123, 'STRING']));
    }

    /**
    *   @test
    */
    public function useCreate()
    {
//      $this->markTestIncomplete();

        $obj = new PrintfMessageGenerator();
        $this->assertEquals('', $obj->generate());

        $msg = 'only string';
        $obj = PrintfMessageGenerator::create($msg);
        $this->assertEquals($msg, $obj->generate());
    }
}
