<?php

declare(strict_types=1);

namespace test\Concerto\auth\authentication;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\auth\authentication\SimpleAuthUserRepositoryImpl;
use Concerto\auth\authentication\AuthUserInterface;

class SimpleAuthUserRepositoryImplTest extends ConcertoTestCase
{
    /**
    */
    #[Test]
    public function basicSuccess()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

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
