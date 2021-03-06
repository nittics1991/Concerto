<?php

/**
*   Simple Table Definition
*
*   @version 170202
*/

declare(strict_types=1);

namespace Concerto\sql\simpleTable;

use Concerto\sql\simpleTable\TableDefinition;

class Sqlite extends TableDefinition
{
    /**
    *   {inherit}
    *
    */
    protected $columnMap = [
        'boolean' => 'TEXT',
        'integer' => 'INTEGER',
        'double' => 'REAL',
        'string' => 'TEXT',
        'datetime' => 'TEXT'
    ];

    /**
    *   {inherit}
    *
    */
    protected function convertSchema(string $schema): string
    {
        if (mb_strpos($schema, '.') === false) {
            return $schema;
        }
        return (string)mb_ereg_replace('\.', '_', $schema);
    }
}
