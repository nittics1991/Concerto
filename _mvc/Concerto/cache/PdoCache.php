<?php

/**
*   PdoCache
*
*   @version 221201
*
*   テーブル列定義
*       keyColumn:キー TEXT PRIMARY KEY
*       valueColumn:値 TEXT NOT NULL
*       expireColumn:有効期限 TIMESTAMP NOT NULL
*/

declare(strict_types=1);

namespace Concerto\cache;

use DateTimeImmutable;
use PDO;
use Psr\SimpleCache\CacheInterface;
use Concerto\cache\SimpleCacheTrait;

class PdoCache implements CacheInterface
{
    use SimpleCacheTrait;

    /**
    *   @var PDO
    */
    protected PDO $pdo;

    /**
    *   @var string
    */
    protected string $tableName;

    /**
    *   @var string
    */
    protected string $keyColumnName;

    /**
    *   @var string
    */
    protected string $valueColumnName;

    /**
    *   @var string
    */
    protected string $expireColumnName;

    /**
    *   __construct
    *
    *   @param PDO $pdo
    *   @param string $tableName
    *   @param string $keyColumnName
    *   @param string $valueColumnName
    *   @param string $expireColumnName
    */
    public function __construct(
        PDO $pdo,
        string $tableName = 'PdoCache',
        string $keyColumnName = 'key',
        string $valueColumnName = 'value',
        string $expireColumnName = 'expire_at'
    ) {
        $this->pdo = $pdo;

        $this->tableName = $tableName;

        $this->keyColumnName = $keyColumnName;

        $this->valueColumnName = $valueColumnName;

        $this->expireColumnName = $expireColumnName;

        $this->pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
    }

    /**
    *   @inheritDoc
    */
    public function get(
        string $key,
        mixed $default = null
    ): mixed {
        $this->validateKey($key);

        $sql = "
            SELECT *
            FROM {$this->tableName}
            WHERE {$this->keyColumnName} = :key
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':key', $key, PDO::PARAM_STR);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($row)) {
            return $default;
        }

        $expire = new DateTimeImmutable(
            strval($row["{$this->expireColumnName}"])
        );

        if ($expire < (new DateTimeImmutable())) {
            $this->delete($key);

            return $default;
        }

        return  json_decode(
            strval($row["{$this->valueColumnName}"])
        );
    }

    /**
    *   @inheritDoc
    */
    public function set(
        string $key,
        mixed $value,
        null|int|\DateInterval $ttl = null
    ): bool {
        $this->validateKey($key);

        $ttl = $this->parseExpire($ttl);

        $sql = "
            INSERT INTO {$this->tableName} AS A (
                {$this->keyColumnName},
                {$this->valueColumnName},
                {$this->expireColumnName}
                )
            VALUES
                (:key, :value, :expire)
            ON CONFLICT ({$this->keyColumnName})
            DO UPDATE SET
                {$this->valueColumnName} = :value,
                {$this->expireColumnName} = :expire
            WHERE A.{$this->keyColumnName} = :key
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':key', $key, PDO::PARAM_STR);

        $value = json_encode($value);

        $stmt->bindValue(':value', $value, PDO::PARAM_STR);

        $expire = (new DateTimeImmutable())
            ->modify("{$ttl} sec")
            ->format('Y-m-d H:i:s');

        $stmt->bindValue(':expire', $expire, PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
    *   @inheritDoc
    */
    public function delete(
        string $key
    ): bool {
        $this->validateKey($key);

        $sql = "
            DELETE FROM {$this->tableName}
            WHERE {$this->keyColumnName} = :key
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':key', $key, PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
    *   @inheritDoc
    */
    public function clear(): bool
    {
        $sql = "
            DELETE FROM {$this->tableName}
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute();
    }
}
