<?php

declare(strict_types=1);

namespace test\Concerto\template;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\template\CurlyBracketMessageGenerator;

class CurlyBracketMessageGeneratorTest extends ConcertoTestCase
{
    /**
    */
    #[Test]
    public function first()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new CurlyBracketMessageGenerator();
        $this->assertEquals('', $obj->generate());

        $msg = 'only string';
        $obj = new CurlyBracketMessageGenerator($msg);
        $this->assertEquals($msg, $obj->generate());

        $msg = 'example number={{no}}, string {{str}}';
        $actual = 'example number=123, string STRING';
        $obj = new CurlyBracketMessageGenerator($msg);
        $this->assertEquals($actual, $obj->generate(['no' => 123, 'str' => 'STRING']));
    }

    /**
    */
    #[Test]
    public function useCreate()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new CurlyBracketMessageGenerator();
        $this->assertEquals('', $obj->generate());

        $msg = 'only string';
        $obj = CurlyBracketMessageGenerator::create($msg);
        $this->assertEquals($msg, $obj->generate());
    }
}
