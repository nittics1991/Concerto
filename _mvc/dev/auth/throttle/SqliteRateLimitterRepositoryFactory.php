<?php

/**
*   SqliteRateLimitterRepositoryFactory
*
*   @version 240627
*/

declare(strict_types=1);

namespace Concerto\auth\throttle;

use PDO;
use Concerto\auth\throttle\RateLimitterRepositoryFactoryInterface;

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
    private string $expirationColumnName;

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
    *   @param ?string $expirationColumnName
    */
    public function __construct(
        string $filePath = null,
        string $tableName = null,
        string $idColumnName = null,
        string $expirationColumnName = null,
    ) {
        $this->filePath = $filePath;

        $this->tableName = $tableName ?? 'rate_limit';

        $this->idColumnName = $idColumnName ?? 'id';

        $this->expirationColumnName = $expirationColumnName ?? 'expiration';
    }

    /**
    *   create
    *
    *   @param ?string $filePath
    *   @return PDO
    */
    public static function create(
        ?string $filePath = null,
    ): PDO {
        $obj = new self($filePath);

        return $obj->build();
    }

    /**
    *   {inheritDoc}
    *
    *   @return PDO
    */
    public function build(): PDO
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

        return new PDO(
            $this->createPDO(
                "sqlite:{$this->filePath}"
            ),
            $this->tableName,
            $this->idColumnName,
            $this->expirationColumnName,
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
                {$this->idColumnName} TEXT PRIMARY KEY,
                {$this->expirationColumnName} INTEGER NOT NULL
            )
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute();

        return $pdo;
    }
}
