<?php

declare(strict_types=1);

namespace test\Concerto\template;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\template\PrintfMessageGenerator;

class PrintfMessageGeneratorTest extends ConcertoTestCase
{
    /**
    */
    #[Test]
    public function first()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

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
    */
    #[Test]
    public function useCreate()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new PrintfMessageGenerator();
        $this->assertEquals('', $obj->generate());

        $msg = 'only string';
        $obj = PrintfMessageGenerator::create($msg);
        $this->assertEquals($msg, $obj->generate());
    }
}
