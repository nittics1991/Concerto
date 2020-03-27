<?php

declare(strict_types=1);

namespace Concerto\test\hashing;

use Concerto\test\ConcertoTestCase;
use Concerto\hashing\OpenSslRandomNumberGenarator;

class OpenSslRandomNumberGenaratorTest extends ConcertoTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }
    
    /**
    *   @test
    */
    public function main()
    {
//      $this->markTestIncomplete();
        
        $obj = new OpenSslRandomNumberGenarator();
        $hash = $obj->generate();
        $this->assertEquals(16 * 2, mb_strlen($hash));
        
        $obj = new OpenSslRandomNumberGenarator(20);
        $hash = $obj->generate();
        $this->assertEquals(20 * 2, mb_strlen($hash));
    }
}
