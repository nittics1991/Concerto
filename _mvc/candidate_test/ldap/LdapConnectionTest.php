<?php

declare(strict_types=1);

namespace test\Concerto\ldap;

use test\Concerto\ConcertoTestCase;
use candidate\ldap\LdapConnection;
use LDAP\Connection;

/**
*   @requires extension ldap
*/
class LdapConnectionTest extends ConcertoTestCase
{
    protected function setUp(): void
    {
        if(!extension_loaded('ldap')) {
            $this->markTestSkipped('---must be config ldap in php.ini---');
        }
        
        if (
            !isset($_SERVER['USERDOMAIN']) ||
            !mb_ereg_match('toshiba', $_SERVER['USERDOMAIN'], 'i')
        ) {
            $this->markTestSkipped('会社で実行のこと');
            return;
        }

        $this->userId = 'w11308ic@toshiba.local';
        $this->password = '';   //ADが空欄でも通る設定になっている
    }

    public function basicSuccessProvider()
    {
        return [
            [
                'ldap://tsb-sv203.toshiba.local',
                [],
            ],
            [
                'ldap://tsb-sv203.toshiba.local:389',
                [],
            ],
            [
                'ldap://tsb-sv203.toshiba.local:389',
                [
                    LDAP_OPT_PROTOCOL_VERSION => 3,
                    LDAP_OPT_REFERRALS => 0,
                ],
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider basicSuccessProvider
    */
    public function anonymousSuccessSuccess($dns, $options)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new LdapConnection($dns, $options);
        $obj->bind(null, null);
        $this->assertEquals(true, $obj->getConnection() instanceof Connection);
        $obj->unbind();
        $this->assertEquals(null, $obj->getConnection());
    }

    /**
    *   @test
    *   @dataProvider basicSuccessProvider
    */
    public function namedUserSuccess($dns, $options)
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new LdapConnection($dns, $options);
        $obj->bind($this->userId, $this->password);
        $this->assertEquals(true, $obj->getConnection() instanceof Connection);
        $obj->unbind();
        $this->assertEquals(null, $obj->getConnection());
    }

    /**
    *   @test
    */
    public function bindNonPasswordSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');


        $dns = 'ldap://tsb-sv203.toshiba.local';
        $options = [];

        $obj = new LdapConnection($dns, $options);
        $obj->bind($this->userId);
        $this->assertEquals(true, $obj->getConnection() instanceof Connection);
    }

    /**
    *   @test
    */
    public function dummyUserNoPasswordSuccess()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $dns = 'ldap://tsb-sv203.toshiba.local';
        $options = [];

        // $this->expectException(\ErrorException::class);
        $obj = new LdapConnection($dns, $options);
        $obj->bind('DUMMY');
        $this->assertEquals(true, $obj->getConnection() instanceof Connection);
    }

    /**
    *   @test
    */
    public function dnsFailure()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $dns = 'ldap://DUMMY';
        $options = [];

        $this->expectException(\ErrorException::class);
        $obj = new LdapConnection($dns, $options);
        $obj->bind(null, null);
    }

    /**
    *   @test
    */
    public function passwordFailure()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $dns = 'ldap://tsb-sv203.toshiba.local';
        $options = [];

        $this->expectException(\ErrorException::class);
        $obj = new LdapConnection($dns, $options);
        $obj->bind($this->userId, 'DUMMY');
    }

    /**
    *   @test
    */
    public function optionFailure()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $dns = 'ldap://tsb-sv203.toshiba.local';
        $options = [
            'DUMMY' => 9999,
        ];

        $this->expectException(\TypeError::class);
        $obj = new LdapConnection($dns, $options);
        $obj->bind(null, null);
    }

    /**
    *   @test
    */
    public function setOptionSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $dns = 'ldap://tsb-sv203.toshiba.local';
        $options = [];

        $obj = new LdapConnection($dns, $options);

        $actual = [
            LDAP_OPT_DEREF => 2,
            LDAP_OPT_SIZELIMIT => 100,
        ];

        foreach ($actual as $name => $val) {
            $obj->setOption($name, $val);
        }

        $expect = $this->getPrivateProperty($obj, 'options');
        $this->assertEquals($actual, $expect);
    }

    /**
    *   @test
    */
    public function setOptionsSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $dns = 'ldap://tsb-sv203.toshiba.local';
        $options = [];

        $obj = new LdapConnection($dns, $options);

        $actual = [
            LDAP_OPT_DEREF => 2,
            LDAP_OPT_SIZELIMIT => 100,
        ];

        $obj->setOptions($actual);

        $expect = $this->getPrivateProperty($obj, 'options');
        $this->assertEquals($actual, $expect);
    }

    /**
    *   @test
    */
    public function getBoundOptionSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $dns = 'ldap://tsb-sv203.toshiba.local';
        $options = [
            LDAP_OPT_DEREF => 2,
            LDAP_OPT_SIZELIMIT => 100,

        ];

        $obj = new LdapConnection($dns, $options);
        $obj->bind();

        foreach ($options as $name => $val) {
            $expect = $obj->getBoundOption($name);
            $this->assertEquals($val, $expect);
        }
    }

    /**
    *   @test
    */
    public function getBoundOptionFailure()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $dns = 'ldap://tsb-sv203.toshiba.local';
        $options = [];

        $this->expectException(\RuntimeException::class);
        $obj = new LdapConnection($dns, $options);
        $obj->getBoundOption(LDAP_OPT_SIZELIMIT);
    }

    /**
    *   @test
    */
    public function attacheOptionFailure()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $dns = 'ldap://tsb-sv203.toshiba.local';
        $options = [];

        $this->expectException(\RuntimeException::class);
        $obj = new LdapConnection($dns, $options);
        $obj->attacheOption(LDAP_OPT_SIZELIMIT, 100);
    }

    /**
    *   @test
    */
    public function attachOptionSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $dns = 'ldap://tsb-sv203.toshiba.local';
        $options = [];

        $obj = new LdapConnection($dns, $options);
        $obj->bind();

        $actual = [
            LDAP_OPT_DEREF => 2,
            LDAP_OPT_SIZELIMIT => 100,
        ];

        foreach ($actual as $name => $val) {
            $obj->attacheOption($name, $val);
        }

        foreach ($actual as $name => $val) {
            $expect = $obj->getBoundOption($name);
            $this->assertEquals($val, $expect);
        }
    }

    /**
    *   @test
    */
    public function setTslSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $dns = 'ldap://tsb-sv203.toshiba.local';
        $options = [];

        $obj = new LdapConnection($dns, $options);
        $obj->setTsl();

        $expect = $this->getPrivateProperty($obj, 'tsl');
        $this->assertEquals(true, $expect);
    }
}
