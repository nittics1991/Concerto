<?php

declare(strict_types=1);

namespace test\Concerto\ldap;

use test\Concerto\ConcertoTestCase;
use candidate\ldap\LdapStmt;
use candidate\ldap\LdapQuery;
use candidate\ldap\LdapConnection;

class LdapStmtTest extends ConcertoTestCase
{
    protected function setUser(): void
    {
        if (
            !isset($_SERVER['USERDOMAIN']) ||
            !mb_ereg_match('toshiba', $_SERVER['USERDOMAIN'], 'i')
        ) {
            $this->markTestSkipped('会社で実行のこと');
            return;
        }

        $this->userId = 'w11308ic@toshiba.local';
        $this->password = '';
    }

    protected function setUp(): void
    {
        if(!extension_loaded('ldap')) {
            $this->markTestSkipped('---must be config ldap in php.ini---');
        }
        
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

    protected function buildStatement($baseDn, $filter)
    {
        $stmt = $this->obj->search(
            $baseDn,
            $filter
        );

        if (!$stmt instanceof LdapStmt) {
            throw \RuntimeException("stmt create failed");
        }
        return $stmt;
    }

    /**
    *   @test
    */
    public function emptyStatementSuccess()
    {
        $this->markTestIncomplete('--- markTestIncomplete ---');

        $baseDn = 'OU=All Toshiba Rooms,DC=toshiba,DC=local';
        $filter = '(name=DUMMY*)';
        $stmt = $this->buildStatement($baseDn, $filter);

        $val = $stmt->getIterator();
        $this->assertEquals([], iterator_to_array($val));
    }

    /**
    *   @test
    */
    public function validStatementSuccess()
    {
        $this->markTestIncomplete('--- markTestIncomplete ---');

        $baseDn = 'OU=All Toshiba Rooms,DC=toshiba,DC=local';
        $filter = '(name=ITC-0*)';
        $stmt = $this->buildStatement($baseDn, $filter);

        $i = 0;
        $limit = 0;
        foreach ($stmt as $entry) {
            $attributes = $entry->getAttributes();
            $this->assertEquals(true, count($attributes) > 0);

            if ($i >= $limit) {
                break;
            }
            $i++;
        }

        $stmt->free();
    }
}
