<?php

declare(strict_types=1);

namespace test\Concerto\url;

use test\Concerto\ConcertoTestCase;
use candidate\view\HtmlPattern;

class HtmlPatternTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function addPattern()
    {
//      $this->markTestIncomplete();

        $pattern = [
            'int' => '^(+|-){0,1}\d+$',
            'date' => '^20\d{2}-\d{2}-\d{2}$',
        ];

        $obj = new HtmlPattern($pattern);
        $this->assertEquals($pattern['date'], $obj('date'));
        $this->assertEquals('', $obj('dummy'));
    }

    public function initPatternProvider()
    {
        $obj = new HtmlPattern();
        $patterns = $this->getPrivateProperty($obj, 'patterns');
        return array_map(
            null,
            array_keys($patterns),
            array_values($patterns),
        );
    }

/**
    *   @test
    *   @dataProvider initPatternProvider
    *   @group default
    */
    public function initPattern(
        $name,
        $expect,
    ) {
//      $this->markTestIncomplete();

        $obj = new HtmlPattern();
        //invoke
        $this->assertEquals(
            $expect,
            $obj($name),
        );
        //call
        $this->assertEquals(
            $expect,
            $obj->$name(),
        );
        //get
        $this->assertEquals(
            $expect,
            $obj->$name,
        );
    }
}
