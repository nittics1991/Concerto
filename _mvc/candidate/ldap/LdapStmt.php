<?php

/**
*   LdapStmt
*
*   @version 210614
*/

declare(strict_types=1);

namespace candidate\ldap;

use IteratorAggregate;
use RuntimeException;
use Traversable;
use candidate\ldap\LdapConnection;
use candidate\ldap\LdapEntry;

class LdapStmt implements IteratorAggregate
{
    /**
    *   connection
    *
    *   @var LdapConnection
    */
    protected $connection;

    /**
    *   resultId
    *
    *   @var resource
    */
    protected $resultId;

    /**
    *   __construct
    *
    *   @param LdapConnection $connection
    *   @param resource $resultId
    */
    public function __construct(
        LdapConnection $connection,
        $resultId
    ) {
        $this->connection = $connection;
        $this->resultId = $resultId;
    }

    /**
    *   __destruct
    *
    */
    public function __destruct()
    {
        $this->free();
    }

    /**
    *   free
    *
    *   @return void
    */
    public function free()
    {
        @ldap_free_result($this->resultId);
    }

    /**
    *   {inherit}
    *
    *   @return Traversable
    */
    public function getIterator(): Traversable
    {
        $cnount = ldap_count_entries(
            $this->connection->getConnection(),
            $this->resultId
        );

        if ($cnount === 0) {
            return [];
        }

        $id = ldap_first_entry(
            $this->connection->getConnection(),
            $this->resultId
        );

        if ($id === false) {
            throw new RuntimeException(
                "failed to get entry data"
            );
        }

        yield $this->getEntry($id);

        while (
            $id = ldap_next_entry(
                $this->connection->getConnection(),
                $id
            )
        ) {
            yield $this->getEntry($id);
        }
    }

    /**
    *   エントリー取得
    *
    *   @param resource $id
    *   @return LdapEntry
    */
    private function getEntry($id): LdapEntry
    {
        $dn = ldap_get_dn(
            $this->connection->getConnection(),
            $id
        );

        if ($dn === false) {
            throw new RuntimeException(
                "can not get DN:{$dn}"
            );
        }

        $attributes = ldap_get_attributes(
            $this->connection->getConnection(),
            $id
        );

        //キーが数値のデータはカラム名の為削除する
        $keyExcluded = $this->excludeNumberKeyFromAttribute($attributes);
        //各属性にあるcountカラムを削除
        $noCountAttribute =
            $this->excludeCountKeyFromAttribute($keyExcluded);

        return new LdapEntry($dn, $noCountAttribute);
    }

    /**
    *   属性から数値キー(カラム名)データを削除する
    *
    *   @param mixed[] $attributes
    *   @return mixed[]
    */
    private function excludeNumberKeyFromAttribute(array $attributes): array
    {
        return array_diff_key(
            $attributes,
            array_flip(range(0, count($attributes) - 1)) + ['count' => null]
        );
    }

    /**
    *   属性から数値キー(カラム名)データを削除する
    *
    *   @param mixed[] $attributes
    *   @return mixed[]
    */
    private function excludeCountKeyFromAttribute(array $attributes): array
    {
        return array_map(
            function ($list) {
                unset($list['count']);
                return $list;
            },
            $attributes
        );
    }
}
