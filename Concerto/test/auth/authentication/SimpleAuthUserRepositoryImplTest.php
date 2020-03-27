<?php

declare(strict_types=1);

namespace Concerto\test\auth\authentication;

use Concerto\test\ConcertoTestCase;
use Concerto\auth\authentication\SimpleAuthUserRepositoryImpl;
use Concerto\auth\authentication\AuthUserInterface;

class SimpleAuthUserRepositoryImplTest extends ConcertoTestCase
{
    /**
    *   @test
    **/
    public function basicSuccess()
    {
        // $this->markTestIncomplete();
        
        $users = [
            'aaa' => 'AAA',
            'bbb' => 'BBB',
            'ccc' => 'CCC',
        ];
        
        $obj = new SimpleAuthUserRepositoryImpl($users);
        
        $expect = $obj->findByUserId('bbb');
        
        $this->assertEquals(true, $expect instanceof AuthUserInterface);
        $this->assertEquals('bbb', $expect->getId());
        $this->assertEquals('BBB', $expect->getPassword());
    }
}
