<?php

declare(strict_types=1);

namespace Concerto\test\template;

use Concerto\test\ConcertoTestCase;
use Concerto\template\CurlyBracketMessageGenerator;

class CurlyBracketMessageGeneratorTest extends ConcertoTestCase
{
    /**
    *   @test
    **/
    public function first()
    {
//      $this->markTestIncomplete();
        
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
    *   @test
    **/
    public function useCreate()
    {
//      $this->markTestIncomplete();
        
        $obj = new CurlyBracketMessageGenerator();
        $this->assertEquals('', $obj->generate());
        
        $msg = 'only string';
        $obj = $obj->create($msg);
        $this->assertEquals($msg, $obj->generate());
    }
}
