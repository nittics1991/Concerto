<?php

declare(strict_types=1);

namespace Concerto\test\hashing;

use Concerto\test\ConcertoTestCase;
use Concerto\hashing\StandardHasher;

class StandardHasherTest extends ConcertoTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }
    
    public function mainProvider()
    {
        return [
            [
                'password',
                []
            ],
            [
                'vj@03 vン:あwfkv-^2居r-「hgbmv」vlg[q3',
                ['cost ' => 20]
            ],
        ];
    }
    
    /**
    *   @test
    *   @dataProvider mainProvider
    */
    public function main($password, $options)
    {
//      $this->markTestIncomplete();
        
        $obj = new StandardHasher($options);
        
        $hash = $obj->hash($password);
        $this->assertEquals(true, $obj->check($hash));
        $this->assertEquals(true, $obj->verify($password, $hash));
    }
}
