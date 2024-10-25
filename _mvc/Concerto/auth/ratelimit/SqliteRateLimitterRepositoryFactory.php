<?php

/**
*   SqliteRateLimitterRepositoryFactory
*
*   @version 240703
*/

declare(strict_types=1);

namespace Concerto\auth\ratelimit;

use PDO;
use Concerto\auth\ratelimit\{
    RateLimitterRepository,
    RateLimitterRepositoryFactoryInterface,
};

class SqliteRateLimitterRepositoryFactory implements
    RateLimitterRepositoryFactoryInterface
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
    private string $idColumnName;

    /**
    *   @var string
    */
    private string $timestampColumnName;

    /**
    *   @var string
    */
    private string $defaultFileName = 'rate_limitter.sqlite';

    /**
    *   __construct
    *
    *   @param ?string $filePath
    *   @param ?string $tableName
    *   @param ?string $idColumnName
    *   @param ?string $timestampColumnName
    */
    public function __construct(
        string $filePath = null,
        string $tableName = null,
        string $idColumnName = null,
        string $timestampColumnName = null,
    ) {
        $this->filePath = $filePath;

        $this->tableName = $tableName ?? 'rate_limit';

        $this->idColumnName = $idColumnName ?? 'id';

        $this->timestampColumnName = $timestampColumnName ?? 'create_at';
    }

    /**
    *   create
    *
    *   @param ?string $filePath
    *   @return RateLimitterRepository
    */
    public static function create(
        ?string $filePath = null,
    ): RateLimitterRepository {
        $obj = new self($filePath);

        return $obj->build();
    }

    /**
    *   {inheritDoc}
    *
    *   @return RateLimitterRepository
    */
    public function build(): RateLimitterRepository
    {
        if ($this->filePath === null) {
            $tempPath = getenv('TEMP');

            $tempPath = $tempPath === false ?
                '/tmp' :
                $tempPath;

            $this->filePath =
                $tempPath .
                DIRECTORY_SEPARATOR .
                $this->defaultFileName;
        }

        return new RateLimitterRepository(
            $this->createPDO(
                "sqlite:{$this->filePath}"
            ),
            $this->tableName,
            $this->idColumnName,
            $this->timestampColumnName,
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
                {$this->idColumnName} TEXT NOT NULL,
                {$this->timestampColumnName} INTEGER NOT NULL
            )
        ";

        $pdo->exec($sql);

        $sql = "
            CREATE INDEX IF NOT EXISTS {$this->tableName}_idx
                ON {$this->tableName}
                (
                    {$this->idColumnName}
                )
        ";

        $pdo->exec($sql);

        return $pdo;
    }
}
