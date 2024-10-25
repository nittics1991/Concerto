<?php

/**
*   RateLimitterRepository
*
*   @version 240704
*/

declare(strict_types=1);

namespace Concerto\auth\ratelimit;

use PDO;
use Concerto\auth\ratelimit\{
    RateLimitterItemInterface,
    RateLimitterRepositoryInterface,
};

class RateLimitterRepository implements RateLimitterRepositoryInterface
{
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
    protected string $idColumnName;

    /**
    *   @var string
    */
    protected string $timestampColumnName;

    /**
    *   __construct
    *
    *   @param PDO $pdo
    *   @param string $tableName
    *   @param string $idColumnName
    *   @param string $timestampColumnName
    */
    public function __construct(
        PDO $pdo,
        string $tableName,
        string $idColumnName,
        string $timestampColumnName,
    ) {
        $this->pdo = $pdo;
        $this->tableName = $tableName;
        $this->idColumnName = $idColumnName;
        $this->timestampColumnName = $timestampColumnName;
    }

    /**
    *   {inheritDoc}
    */
    public function save(
        string $id,
    ) {
        $sql = "
            INSERT INTO {$this->tableName} (
                {$this->idColumnName},
                {$this->timestampColumnName}
            ) VALUES(
                :id,
                :timestamp
            )
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(
            ':id',
            $id,
            PDO::PARAM_STR,
        );

        $now = time();

        $stmt->bindValue(
            ':timestamp',
            $now,
            PDO::PARAM_INT,
        );

        $stmt->execute();
    }

    /**
    *   {inheritDoc}
    *
    *   @param int $interval sec
    */
    public function fetch(
        string $id,
        int $interval,
    ): array {
        $sql = "
            SELECT *
            FROM {$this->tableName}
            WHERE {$this->idColumnName} = :id
                AND  {$this->timestampColumnName} >= :timestamp
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(
            ':id',
            $id,
            PDO::PARAM_STR,
        );

        $duration = time() - $interval;

        $stmt->bindValue(
            ':timestamp',
            $duration,
            PDO::PARAM_INT,
        );

        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
    *   {inheritDoc}
    *
    *   @param ?int $interval sec NULLで全データ削除
    *   @return void
    */
    public function delete(
        ?int $interval = null,
    ): void {
        $sql = "
            DELETE FROM {$this->tableName}
            WHERE (
                {$this->timestampColumnName} < :timestamp
            ) IS NOT FALSE
        ";

        $stmt = $this->pdo->prepare($sql);

        $duration = $interval ?
            time() - $interval :
            time() + 1;

        $stmt->bindValue(
            ':timestamp',
            $duration,
            PDO::PARAM_INT,
        );

        $stmt->execute();
    }
}
