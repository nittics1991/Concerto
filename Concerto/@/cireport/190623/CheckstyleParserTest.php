<?php

// namespace Concerto\test\event;

use Concerto\test\ConcertoTestCase;

// use Concerto\event\Event;


require_once 'CheckstyleParser.php';


class CheckstyleParserTest extends ConcertoTestCase
{
    protected function setUp(): void
    {
        $this->obj = $obj = new CheckstyleParser(
            realpath(__DIR__ . './data/pcs.xml')
        );
    }
    
    /**
    *   @test
    */
    public function constructException()
    {
     // $this->markTestIncomplete();
        
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('file not found:DUMMY');
        
        $obj = $obj = new CheckstyleParser('DUMMY');
    }
    
    public function countByPathSuccessProvider()
    {
        return [
            [
                '/DUMMY',
                0,
            ],
            [
                '/checkstyle/file',
                56,
            ],
            [
                '/checkstyle/file/error',
                482,
            ],
        ];
    }
    
    /**
    *   @test
    *   @dataProvider countByPathSuccessProvider
    */
    public function countByPathSuccess($targetTagName, $actual)
    {
        // $this->markTestIncomplete();
        
        $expect = $this->obj->countByPath($targetTagName);
        $this->assertEquals($actual, $expect);
    }
    
    public function groupByPathSuccessProvider()
    {
        return [
            [
                '/DUMMY',
                'TEST',
                0,
            ],
            [
                '/checkstyle/file/error',
                'TEST',
                0,
            ],
            [
                '/checkstyle/file/error',
                'severity',
                2,
            ],
            [
                '/checkstyle/file/error',
                'source',
                3,
            ],
        ];
    }
    
    /**
    *   @test
    *   @dataProvider groupByPathSuccessProvider
    */
    public function groupByPathSuccess($targetTagName, $attributeName, $actual)
    {
        // $this->markTestIncomplete();
        
        $expect = $this->obj->groupByPath($targetTagName, $attributeName);
        $this->assertEquals($actual, count($expect));
    }
}
