<?php

/**
*   TempTableSqlBuilder
*
*   @version 241008
*/

declare(strict_types=1);

namespace Concerto\sql\symphony;

use Concerto\conf\Config;
use Concerto\sql\symphony\SymphonyColumnDef;

class TempTableSqlBuilder
{
    /**
    *   @var Config<string[]>
    */
    private Config $table_defs;

    /**
    *   @var string
    */
    private string $create_table_prefix;

    /**
    *   __construct
    *
    *   @param Config<string[]> $table_defs
    *   @param string $create_table_prefix
    */
    public function __construct(
        Config $table_defs,
        string $create_table_prefix = 'symphony_'
    ) {
        $this->table_defs = $table_defs;

        $this->create_table_prefix = $create_table_prefix;
    }

    /**
    *   build
    *
    *   @param string $table_name
    *   @param string $where
    *   @return array{0:string, 1:string}
    */
    public function build(
        string $table_name,
        string $where = '1 = 1',
    ): array {
        $column_defs = $this->findTableDefs(
            $table_name,
        );

        $table_sql = $this->buildTableSql(
            $table_name,
        );

        $select_sql = $this->buildSelectSql(
            $table_name,
            $where,
        );

        return [$table_sql, $select_sql];
    }

    /**
    *   findTableDefs
    *
    *   @param string $table_name
    *   @return SymphonyColumnDef[]
    */
    private function findTableDefs(
        string $table_name,
    ): array {
        $table_defs = [];

        foreach ($this->table_defs as $row) {
            if (
                isset($row['TABLE_NAME']) &&
                $row['TABLE_NAME'] === $table_name
            ) {
                $table_defs[] = new SymphonyColumnDef(
                    $row,
                );
            }
        }

        return $table_defs;
    }

    /**
    *   buildTableSql
    *
    *   @param string $table_name
    *   @return string
    */
    public function buildTableSql(
        string $table_name,
    ): string {
        $column_defs = $this->findTableDefs(
            $table_name,
        );

        $create_table_name = $this->create_table_prefix .
            mb_strtolower($table_name);

        $sql = "CREATE TEMP TABLE {$create_table_name} (";

        $columns = [];

        foreach ($column_defs as $def) {
            $columns[] = mb_strtolower($def->column_name) .
                ' ' .
                mb_strtolower($def->data_type);
        }

        $sql .= implode(',', $columns) . ')';

        return $sql;
    }

    /**
    *   buildSelectSql
    *
    *   @param string $table_name
    *   @param string $where
    *   @return string
    */
    public function buildSelectSql(
        string $table_name,
        string $where = '1 = 1',
    ): string {
        $column_defs = $this->findTableDefs(
            $table_name,
        );

        $sql = "SELECT ";

        $columns = [];

        foreach ($column_defs as $def) {
            $columns[] = $def->column_name;
        }

        $sql .= implode(',', $columns) .
            " FROM {$table_name}" .
            " WHERE {$where}";

        return $sql;
    }
}
