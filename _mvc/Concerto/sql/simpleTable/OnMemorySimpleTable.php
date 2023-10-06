<?php

/**
*   OnMemorySimpleTable
*
*   @version 210921
*/

declare(strict_types=1);

namespace Concerto\sql\simpleTable;

use Exception;
use InvalidArgumentException;
use PDO;
use Concerto\sql\simpleTable\SimpleTable;
use Concerto\standard\ModelDb;

class OnMemorySimpleTable extends SimpleTable
{
    /**
    *   @inheritDoc
    */
    protected array $type_convert_map = [
        'boolean' => 'TEXT',
        'integer' => 'INTEGER',
        'double' => 'REAL',
        'string' => 'TEXT',
        'datetime' => 'TEXT',
    ];

    /**
    *   @inheritDoc
    */
    public function __construct(
        ?PDO $pdo = null,
    ) {
        $this->pdo = $pdo ??
            new PDO(
                'sqlite::memory:',
                null,
                null,
                [
                    PDO::ATTR_ERRMODE =>
                        PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE =>
                        PDO::FETCH_ASSOC
                ],
            );
    }

    /**
    *   @inheritDoc
    */
    public function getTableName(
        ModelDb $modelDb
    ): string {
        $table_name = $modelDb->getSchema();
        $result = mb_ereg_replace('\.', '_', $table_name);

        if (
            $result === null ||
            $result === false
        ) {
            throw new InvalidArgumentException(
                "failure comma to underscore"
            );
        }
        return $result;
    }

    /**
    *   columns
    *
    *   @param ModelDb|string $table
    *   @return mixed[]
    */
    public function columns(
        ModelDb | string $table
    ): array {
        $table_name = is_object($table) ?
            $this->getTableName($table) :
            $table;

        $sql = "
            PRAGMA table_info({$table_name})
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }

    /**
    *   truncate
    *
    *   @param ModelDb|string $table
    *   @return OnMemorySimpleTable
    */
    public function truncate(
        ModelDb | string $table
    ): OnMemorySimpleTable {
        $table_name = is_object($table) ?
            $this->getTableName($table) :
            $table;

        $this->pdo->beginTransaction();

        try {
            $this->pdo->exec(
                "DELETE FROM {$table_name}"
            );

            $this->pdo->exec(
                "PRAGMA VACUUM"
            );

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
        return $this;
    }
}
