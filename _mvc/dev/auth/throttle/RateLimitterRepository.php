<?php

/**
*   RateLimitterRepository
*
*   @version 240627
*/

declare(strict_types=1);

namespace Concerto\auth\throttle;

use DateTimeImmutable;
use PDO;
use Concerto\auth\throttle\{
    RateLimitterItemInterface,
    RateLimitterRepositoryInterface,
};

class RateLimitterRepository implements RateLimitterRepositoryInterface
{
    /**
    *   @var PDO
    */
    private PDO $pdo;

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
    *   __construct
    *
    *   @param PDO $pdo
    *   @param ?string $tableName
    *   @param ?string $idColumnName
    *   @param ?string $expirationColumnName
    */
    public function __construct(
        PDO $pdo,
        ?string $tableName,
        ?string $idColumnName,
        ?string $expirationColumnName,
    ) {
        $this->pdo = $pdo;
        $this->tableName = $tableName;
        $this->idColumnName = $idColumnName;
        $this->expirationColumnName = $expirationColumnName;
    }

    /**
    *   {inheritDoc}
    */
    public function save(
        RateLimitterItemInterface $item,
    ) {
        $sql = "
            INSERT INTO {$this->tableName} (
                {$this->idColumnName},
                {$this->expirationColumnName}
            ) VALUES(
                :id,
                :expiration
            )
        ";

        $stmt = $this->pdo->prepara($sql);

        $stmt->bindValue(
            ':id',
            $item->getId(),
            PDO::PARAM_STR,
        );

        $stmt->bindValue(
            ':expiration',
            $item->getExpiration(),
            PDO::PARAM_INT,
        );

        $stmt->execute();
    }

    /**
    *   {inheritDoc}
    */
    public function fetch(
        string $id,
    ): array {
        $this->delete();

        $sql = "
            SELECT *
            FROM {$this->tableName}
            WHERE {$this->idColumnName} = :id
        ";

        $stmt = $this->pdo->prepara($sql);

        $stmt->bindValue(
            ':id',
            $id,
            PDO::PARAM_STR,
        );

        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
    *   {inheritDoc}
    *
    *   @param ?string $id NULL:ŠúŒÀØ‚ê‚ð‘S‚Äíœ
    */
    public function delete(
        ?string $id,
    ) {
        $sql = "
            DELETE FROM {$this->tableName}
            WHERE (
                {$this->idColumnName} = :id
                AND {$this->expirationColumnName} < :expiration
            ) IS NOT FALSE
        ";

        $stmt = $this->pdo->prepara($sql);

        $stmt->bindValue(
            ':id',
            $id,
            PDO::PARAM_STR,
        );

        $expiration = (new DateTimeImmutable("now"))
            ->format('U');

        $stmt->bindValue(
            ':expiration',
            intval($expiration),
            PDO::PARAM_INT,
        );

        $stmt->execute();
    }
}
