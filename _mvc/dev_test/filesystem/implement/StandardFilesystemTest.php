<?php

declare(strict_types=1);

namespace test\Concerto\filesystem\implement;

use PHPUnit\Framework\TestCase;
use test\Concerto\{
    PrivateTestTrait,
    TempDirTestHelper,
};
use Concerto\filesystem\implement\StandardFilesystem;

class StandardFilesystemTest extends TestCase
{
    use PrivateTestTrait;
    
    protected $tempHelper;
    
    protected function setUp(): void
    {
        $this->tempHelper = TempDirTestHelper::create();
    }

    public function convertProvider()
    {
        return [
            [
                
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider convertProvider
    */
    public function convert(
        string $path,
        array $context,
        string $type,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');
        
        $obj = new StandardFilesystem();
        
        //$this->tempHelper->root() .
            
        
        
        
        
        
    }






}
