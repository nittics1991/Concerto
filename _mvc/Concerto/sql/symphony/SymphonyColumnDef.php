<?php

/**
*   SymphonyColumnDef
*
*   @version 241008
*/

declare(strict_types=1);

namespace Concerto\sql\symphony;

use ReflectionClass ;
use RuntimeException;

class SymphonyColumnDef
{
    /**
    *   @var array<string, string>
    */
    private array $data_type_tables = [
        'char' => 'text',
        'date' => 'date',
        'number' => 'numeric',
        'varchar2' => 'text',
    ];

    /**
    *   @var string
    */
    public string $table_catalog;

    /**
    *   @var string
    */
    public string $table_schema;

    /**
    *   @var string
    */
    public string $table_name;

    /**
    *   @var string
    */
    public string $column_name;

    /**
    *   @var string
    */
    public string $logical_name;

    /**
    *   @var int
    */
    public int $ordinal_position;

    /**
    *   @var string
    */
    public string $column_default;

    /**
    *   @var bool
    */
    public bool $is_nullable;

    /**
    *   @var string
    */
    public string $data_type;

    /**
    *   @var string
    */
    public string $key_position;

    /**
    *   @var string
    */
    public string $description;

    /**
    *   __construct
    *
    *   @param string[] $dataset
    */
    public function __construct(
        array $dataset,
    ) {
        $refClass = new ReflectionClass($this);

        foreach ($refClass->getProperties() as $prop) {
            $name = $prop->getName();

            $dataset_name = mb_strtoupper($name);

            if (!isset($dataset[$dataset_name])) {
                continue;
            }

            $type = $prop->getType()?->__toString();

            if ($name === 'data_type') {
                $this->$name = $this->convertDataType(
                    $dataset[$dataset_name],
                );
            } elseif ($type === 'int') {
                $this->$name = (int)$dataset[$dataset_name];
            } elseif ($type === 'bool') {
                $this->$name = mb_strtolower(
                    $dataset[$dataset_name]
                ) === 'yes' ?
                    true : false;
            } else {
                $this->$name = $dataset[$dataset_name];
            }
        }
    }

    /**
    *   convertDataType
    *
    *   @param string $symphony_type
    *   @return string
    */
    private function convertDataType(
        string $symphony_type,
    ): string {
        $types = explode('(', $symphony_type);

        $type = mb_strtolower(trim($types[0]));

        if (
            !array_key_exists(
                $type,
                $this->data_type_tables,
            )
        ) {
            throw new RuntimeException(
                "data type not defined:{$symphony_type}"
            );
        }

        return $this->data_type_tables[$type];
    }
}
