<?php

/**
 *   PdoCache
 *
 * @version 190522
 */

declare(strict_types=1);

namespace Concerto\cache;

use DateTime;
use PDO;
use Psr\SimpleCache\CacheInterface;
use Concerto\cache\SimpleCacheTrait;

class PdoCache implements CacheInterface
{
    use SimpleCacheTrait;

    /**
     *   pdo
     *
     * @var PDO
     */
    protected $pdo;

    /**
     *   tableName
     *
     * @var string
     */
    protected $tableName;

    /**
     *   keyColumnName
     *
     * @var string
     */
    protected $keyColumnName;

    /**
     *   valueColumnName
     *
     * @var string
     */
    protected $valueColumnName;

    /**
     *   expireColumnName
     *
     * @var string
     */
    protected $expireColumnName;

    /**
     *   __construct
     *
     * @param PDO $pdo
     * @param string $tableName
     * @param string $keyColumnName
     * @param string $valueColumnName
     * @param string $expireColumnName
     */
    public function __construct(
        PDO $pdo,
        string $tableName = 'PdoCache',
        string $keyColumnName = 'key',
        string $valueColumnName = 'value',
        string $expireColumnName = 'expireAt'
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
     *   {inherit}
     */
    public function get($key, $default = null)
    {
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

        $expire = new DateTime($row["{$this->expireColumnName}"]);

        if ($expire < (new DateTime())) {
            $this->delete($key);
            return $default;
        }
        return  json_decode($row["{$this->valueColumnName}"]);
    }

    /**
     *   {inherit}
     */
    public function set($key, $value, $ttl = null)
    {
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

        $expire = (new DateTime())
            ->modify("{$ttl} sec")
            ->format('Y-m-d H:i:s');
        $stmt->bindValue(':expire', $expire, PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     *   {inherit}
     */
    public function delete($key)
    {
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
     *   {inherit}
     */
    public function clear()
    {
        $sql = "
            truncate {$this->tableName}
        ";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute();
    }
}
