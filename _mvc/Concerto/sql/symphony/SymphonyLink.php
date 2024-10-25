<?php

/**
*   SymphonyLink
*
*   @version 241007
*/

declare(strict_types=1);

namespace Concerto\sql\symphony;

use PDO;
use PDOStatement;
use RuntimeException;

class SymphonyLink
{
    /**
    *   @var string
    */
    private const CSV_SEPARATOR = "\t";

    /**
    *   @var string
    */
    private const CSV_ENCLOSURE = '"';

    /**
    *   @var string
    */
    private const CSV_ESCAPE = '"';

    /**
    *   @var string
    */
    private const CSV_EOL = "\r\n";

    /**
    *   @var string
    */
    private const CSV_NULL = "";

    /**
    *   @var PDO
    */
    private PDO $symphony;

    /**
    *   @var PDO
    */
    private PDO $concerto;

    /**
    *   __construct
    *
    *   @param PDO $symphony
    *   @param PDO $concerto
    */
    public function __construct(
        PDO $symphony,
        PDO $concerto,
    ) {
        if (
            $symphony->getAttribute(PDO::ATTR_DRIVER_NAME) !==
                'oci'
        ) {
            throw new RuntimeException(
                "#1 PDO must be PDO_OCI",
            );
        }

        if (
            $concerto->getAttribute(PDO::ATTR_DRIVER_NAME) !==
                'pgsql'
        ) {
            throw new RuntimeException(
                "#2 PDO must be pdo_pgsql",
            );
        }

        $this->symphony = $symphony;

        $this->concerto = $concerto;

        $this->setPdoAttribute($this->symphony);

        $this->setPdoAttribute($this->concerto);
    }

    /**
    *   setPdoAttribute
    *
    *   @param PDO $connection
    *   @return void
    */
    private function setPdoAttribute(
        PDO $connection,
    ): void {
        $common_attributes = [
            PDO::ATTR_ERRMODE =>
                PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE =>
                PDO::FETCH_ASSOC,
            PDO::ATTR_CASE =>
                PDO::CASE_LOWER,
            PDO::ATTR_ORACLE_NULLS =>
                PDO::NULL_EMPTY_STRING,
        ];

        foreach ($common_attributes as $attribute => $value) {
            $connection->setAttribute(
                $attribute,
                $value,
            );
        }
    }

    /**
    *   createTempTable
    *       作成するTEMP TABLEデータの""はNULLになる
    *
    *   @param string $tableSql
    *   @param string $selectSql
    *   @param array<string,int|float|string|null> $selectParams
    *   @return string
    */
    public function createTempTable(
        string $tableSql,
        string $selectSql,
        array $selectParams = [],
    ): string {
        $table_name = $this->parseTableName($tableSql);

        $this->createTable($tableSql);

        $dataset = $this->readTable(
            $selectSql,
            $selectParams,
        );

        $file = $this->exportCsvFile($dataset);

        $this->importData($table_name, $file);

        return $table_name;
    }

    /**
    *   parseTableName
    *
    *   @param string $tableSql
    *   @return string
    */
    private function parseTableName(
        string $tableSql,
    ): string {
        $sqls = explode(' ', $tableSql);

        $trimds = array_map(
            fn($sql) => trim($sql),
            $sqls,
        );

        $filterds = array_filter(
            $trimds,
            fn($sql) => $sql !== '',
        );

        $pos = null;

        foreach ($filterds as $no => $sql) {
            if (mb_strtolower($sql) === 'table') {
                $pos = $no;
                break;
            }
        }

        if (
            $pos === null ||
            !isset($filterds[$pos + 1])
        ) {
            throw new RuntimeException(
                "faild to get table name:{$tableSql}",
            );
        }

        return $filterds[$pos + 1];
    }

    /**
    *   createTable
    *
    *   @param string $tableSql
    *   @return void
    */
    private function createTable(
        string $tableSql,
    ): void {
        $stmt = $this->concerto->prepare($tableSql);
        $stmt->execute();
    }

    /**
    *   readTable
    *
    *   @param string $selectSql
    *   @param (int|float|string|null)[] $selectParams
    *   @return PDOStatement
    */
    private function readTable(
        string $selectSql,
        array $selectParams,
    ): PDOStatement {
        $stmt = $this->symphony->prepare($selectSql);

        foreach ($selectParams as $name => $value) {
            $bind_type = gettype($value) === 'integer' ?
                PDO::PARAM_INT : PDO::PARAM_STR;

            $stmt->bindValue(
                ":{$name}",
                $value,
                $bind_type,
            );
        }

        $stmt->execute();

        return $stmt;
    }

    /**
    *   exportCsvFile
    *
    *   @param PDOStatement $data
    *   @return string
    */
    private function exportCsvFile(
        PDOStatement $data,
    ): string {
        $file = tempnam(
            sys_get_temp_dir(),
            uniqid(),
        );

        $handle = fopen($file, 'w+');

        if ($handle === false) {
            throw new RuntimeException(
                "CSV file could not be opened",
            );
        }

        foreach ($data as $row_no => $row) {
            mb_convert_variables(
                'UTF8',
                'SJIS',
                $row,
            );

            $null_converteds = array_map(
                fn($val) => $val ?? self::CSV_NULL,
                $row,
            );

            $length = fputcsv(
                $handle,
                $null_converteds,
                self::CSV_SEPARATOR,
                self::CSV_ENCLOSURE,
                self::CSV_ESCAPE,
                self::CSV_EOL,
            );

            if ($length === false) {
                throw new RuntimeException(
                    "CSV write error. row no:{$row_no}",
                );
            }
        }

        fclose($handle);

        return $file;
    }

    /**
    *   importData
    *
    *   @param string $table_name
    *   @param string $file
    *   @return void
    */
    private function importData(
        string $table_name,
        string $file,
    ): void {
        $this->concerto->pgsqlCopyFromFile(
            $table_name,
            $file,
            self::CSV_SEPARATOR,
            self::CSV_NULL,
        );
    }
}
