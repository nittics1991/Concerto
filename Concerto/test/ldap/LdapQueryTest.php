<?php

declare(strict_types=1);

namespace Concerto\test\ldap;

use Concerto\test\ConcertoTestCase;
use Concerto\ldap\LdapQuery;
use Concerto\ldap\LdapConnection;
use Concerto\ldap\LdapStmt;

class LdapQueryTest extends ConcertoTestCase
{
    protected function setUser(): void
    {
        $this->userId = 'w11308ic@toshiba.local';
        $this->password = '';
    }

    protected function setUp(): void
    {
        $this->setUser();

        $dns = 'ldap://tsb-sv203.toshiba.local';
        $this->obj = new LdapQuery(
            (new LdapConnection(
                'ldap://tsb-sv203.toshiba.local',
                [
                    LDAP_OPT_PROTOCOL_VERSION => 3,
                    LDAP_OPT_REFERRALS => 0,
                ]
            ))->bind($this->userId, $this->password)
        );
    }

    public function basicSuccessProvider()
    {
        return [
            [
                'OU=All Toshiba Rooms,DC=toshiba,DC=local',
                '(name=ITC-0*)',
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider basicSuccessProvider
    */
    public function searchSuccess(
        string $baseDn,
        string $filter,
        array $attributes = [],
        int $typeOnly = 0,
        int $sizeLimit = 1000,
        int $timeLimit = 20,
        int $alias = LDAP_DEREF_NEVER
    ) {
        $this->markTestIncomplete();

        $stmt = $this->obj->search(
            $baseDn,
            $filter,
            $attributes,
            $typeOnly,
            $sizeLimit,
            $timeLimit,
            $alias
        );

        $this->assertEquals(true, $stmt instanceof LdapStmt);
    }

    /**
    *   @test
    *   @dataProvider basicSuccessProvider
    */
    public function readSuccess(
        string $baseDn,
        string $filter,
        array $attributes = [],
        int $typeOnly = 0,
        int $sizeLimit = 1000,
        int $timeLimit = 20,
        int $alias = LDAP_DEREF_NEVER
    ) {
        $this->markTestIncomplete();

        $stmt = $this->obj->read(
            $baseDn,
            $filter,
            $attributes,
            $typeOnly,
            $sizeLimit,
            $timeLimit,
            $alias
        );

        $this->assertEquals(true, $stmt instanceof LdapStmt);
    }

    /**
    *   @test
    *   @dataProvider basicSuccessProvider
    */
    public function listSuccess(
        string $baseDn,
        string $filter,
        array $attributes = [],
        int $typeOnly = 0,
        int $sizeLimit = 1000,
        int $timeLimit = 20,
        int $alias = LDAP_DEREF_NEVER
    ) {
        $this->markTestIncomplete();

        $stmt = $this->obj->list(
            $baseDn,
            $filter,
            $attributes,
            $typeOnly,
            $sizeLimit,
            $timeLimit,
            $alias
        );

        $this->assertEquals(true, $stmt instanceof LdapStmt);
    }
}
