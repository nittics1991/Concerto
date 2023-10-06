<?php

declare(strict_types=1);

namespace test\Concerto\ldap;

use test\Concerto\ConcertoTestCase;
use candidate\ldap\LdapEntry;

class LdapEntryTest extends ConcertoTestCase
{
    protected function setUp(): void
    {
        if(!extension_loaded('ldap')) {
            $this->markTestSkipped('---must be config ldap in php.ini---');
        }
        
    }
    
    public function basicProvider()
    {
        return [
            [
                'cn="PC001",ou="EIGYO1",dc="itc",dc="toshiba", dv="local"',
                [
                    'cpu' => 'core-i5',
                    'clock' => '2GHz',
                    'memory' => '4GB',
                ],
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider basicProvider
    */
    public function basic($dn, $attr)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new LdapEntry($dn, $attr);

        $this->assertEquals($dn, $obj->getDn());
        $this->assertEquals($attr, $obj->getAttributes());
        $this->assertEquals(true, $obj->has('clock'));
        $this->assertEquals($attr['clock'], $obj->get('clock'));

        $this->assertEquals(false, $obj->has('DUMMY'));
        $this->assertEquals(null, $obj->has('DUMMY'));
    }
}
