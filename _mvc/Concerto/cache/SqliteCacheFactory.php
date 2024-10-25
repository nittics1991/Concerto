<?php

/**
*   MemcacheFactory
*
*   @version 230202
*/

declare(strict_types=1);

namespace Concerto\cache;

use PDO;
use Concerto\cache\PdoCache;

class SqliteCacheFactory
{
    /**
    *   @var ?string
    */
    private ?string $filePath;

    /**
    *   @var string
    */
    private string $tableName;

    /**
    *   @var string
    */
    private string $keyColumnName;

    /**
    *   @var string
    */
    private string $valueColumnName;

    /**
    *   @var string
    */
    private string $expireColumnName;

    /**
    *   __construct
    *
    *   @param ?string $filePath
    *   @param ?string $tableName
    *   @param ?string $keyColumnName
    *   @param ?string $valueColumnName
    *   @param ?string $expireColumnName
    */
    public function __construct(
        string $filePath = null,
        string $tableName = null,
        string $keyColumnName = null,
        string $valueColumnName = null,
        string $expireColumnName = null,
    ) {
        $this->filePath = $filePath;

        $this->tableName = $tableName ?? 'concerto_cache';

        $this->keyColumnName = $keyColumnName ?? 'key';

        $this->valueColumnName = $valueColumnName ?? 'value';

        $this->expireColumnName = $expireColumnName ?? 'expire_at';
    }

    /**
    *   create
    *
    *   @param ?string $filePath
    *   @return PdoCache
    */
    public static function create(
        ?string $filePath = null,
    ): PdoCache {
        $obj = new self($filePath);

        return $obj->build();
    }

    /**
    *   build
    *
    *   @return PdoCache
    */
    public function build(): PdoCache
    {
        if ($this->filePath === null) {
            $tempPath = getenv('TEMP');

            $tempPath = $tempPath === false ?
                '/tmp' :
                $tempPath;

            $this->filePath =
                $tempPath .
                DIRECTORY_SEPARATOR .
                'SqliteCache.sqlite';
        }

        return new PdoCache(
            $this->createPDO(
                "sqlite:{$this->filePath}"
            ),
            $this->tableName,
            $this->keyColumnName,
            $this->valueColumnName,
            $this->expireColumnName,
        );
    }

    /**
    *   createPDO
    *
    *   @param string $dns
    *   @return PDO
    */
    private function createPDO(
        string $dns,
    ): PDO {
        $pdo = new PDO(
            $dns,
            null,
            null,
            [
                PDO::ATTR_ERRMODE =>
                    PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE =>
                    PDO::FETCH_ASSOC
            ],
        );

        return $this->hasCacheTable($pdo) ?
            $pdo :
            $this->createCacheTable($pdo);
    }

    /**
    *   hasCacheTable
    *
    *   @param PDO $pdo
    *   @return bool
    */
    private function hasCacheTable(
        PDO $pdo,
    ): bool {
        $sql = "
            PRAGMA table_info({$this->tableName})
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute();

        return !empty((array)$stmt->fetchAll());
    }

    /**
    *   createCacheTable
    *
    *   @param PDO $pdo
    *   @return PDO
    */
    private function createCacheTable(
        PDO $pdo,
    ): PDO {
        $sql = "
            CREATE TABLE IF NOT EXISTS {$this->tableName} (
                {$this->keyColumnName} TEXT PRIMARY KEY,
                {$this->valueColumnName} TEXT NOT NULL,
                {$this->expireColumnName} TIMESTAMP NOT NULL
            )
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute();

        return $pdo;
    }
}
