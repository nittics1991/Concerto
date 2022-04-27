<?php

/**
*   ModelDbExtensionCommandTrait
*
*   @version 220203
*/

declare(strict_types=1);

namespace Concerto\standard\modeldb;

use finfo;
use InvalidArgumentException;
use PDO;
use RuntimeException;
use Concerto\standard\DataModelInterface;

trait ModelDbExtensionCommandTrait
{
    /**
    *   COPY RECORDS
    *
    *   @param DataModelInterface $where 条件
    *   @param DataModelInterface $replace
    *                       置換フィールド(指定した値で置換、他は元データコピー)
    *   @return void
    *   @throws InvalidArgumentException
    *   @example $where(no_cyu, no_sheet)
    *                       $replace(update, editor, no_cyu, no_sheet_max)
    */
    public function copyRecord(
        DataModelInterface $where,
        DataModelInterface $replace
    ): void {
        $class_name = $this->entityName();

        if (
            !($where instanceof $class_name) ||
            !($replace instanceof $class_name)
        ) {
            throw new InvalidArgumentException(
                "data type error:{$class_name}"
            );
        }

        $replace_columns = [];
        $replace_values = [];
        $columns = [];
        $where_fields = [];

        foreach ($replace->toArray() as $key => $val) {
            $replace_columns[] = $key;
            $replace_values[] = ":{$key}";
        }

        foreach ($replace->getInfo() as $key => $val) {
            if (!in_array($key, $replace_columns)) {
                $columns[] = $key;
            }
        }

        $sql = "INSERT INTO {$this->schema} ";
        $sql .= sprintf(
            "(%s, %s) SELECT %s, %s FROM {$this->schema} ",
            implode(',', $replace_columns),
            implode(',', $columns),
            implode(',', $replace_values),
            implode(',', $columns)
        );

        foreach ($where->toArray() as $key => $val) {
            $where_fields[] = "{$key} = :where_{$key}";
        }

        if (count($where_fields)) {
            $sql .= sprintf(
                "WHERE %s ",
                implode(' AND ', $where_fields)
            );
        }

        $stmt = $this->pdo->prepare($sql);
        $this->bind($stmt, $replace);
        $this->bind($stmt, $where, ':where_');
        $stmt->execute();
    }

    /**
    *   ファイルインポート(copy from)
    *
    *   @param string $file ファイルパス
    *   @params string[] $params パラメータ [delimiter, null]
    *   @throws InvalidArgumentException, RuntimeException
    */
    public function import(
        string $file,
        array $params = []
    ): void {
        if (
            $this->pdo->getAttribute(
                PDO::ATTR_DRIVER_NAME
            ) != 'pgsql'
        ) {
            throw new RuntimeException(
                "invalid DB driver"
            );
        }

        if (!file_exists($file)) {
            throw new InvalidArgumentException(
                "file not found:{$file}"
            );
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = strtolower((string)$finfo->file($file));

        if (
            $mime != 'text' &&
            $mime != 'text/plain' &&
            $mime != 'text/csv' &&
            $mime != 'application/csv'
        ) {
            throw new InvalidArgumentException(
                "different MIME type:{$mime}"
            );
        }

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (!in_array($ext, ['csv', 'txt', 'tsv','prn'])) {
            throw new InvalidArgumentException(
                "different EXT type:{$ext}"
            );
        }

        if (
            !is_array($params) ||
            !$this->isValidCopyParams($params)
        ) {
            throw new InvalidArgumentException(
                "invalid parameter"
            );
        }

        $delimiter  = !isset($params['delimiter']) ?
            ',' : $params['delimiter'];
        $null = !isset($params['null']) ? "\\\\N" : $params['null'];

        $this->pdo->pgsqlCopyFromFile(
            $this->name,
            $file,
            $delimiter,
            $null
        );
    }

    /**
    *   COPYコマンドバリデーション
    *
    *   @param string[] $params
    *   @return bool
    */
    protected function isValidCopyParams(
        array $params
    ): bool {
        foreach ($params as $key => $val) {
            switch ($key) {
                case 'delimiter':
                    if (
                        !mb_check_encoding((string)$val) ||
                        strlen($val) !== 1
                    ) {
                        return false;
                    }
                    break;
                case 'null':
                    if (!mb_check_encoding((string)$val)) {
                        return false;
                    }
                    break;
                default:
                    return false;
            }
        }
        return true;
    }

    /**
    *   UPSERT
    *
    *   @param DataModelInterface $data データ
    *   @param DataModelInterface $where 条件
    */
    public function upsert(
        DataModelInterface $data,
        DataModelInterface $where
    ): void {
        $values = [];
        foreach ($data->toArray() as $key => $val) {
            if (!is_null($val)) {
                $values[$key] = ":{$key}";
            }
        }

        if (empty($values)) {
            return;
        }

        $wheres = [];
        foreach ($where->toArray() as $key => $val) {
            if (!is_null($val)) {
                $wheres[$key] = ":_{$key}";
            }
        }

        if (empty($wheres)) {
            return;
        }

        $sql = "INSERT INTO {$this->name} AS Z (";
        $sql .= implode(',', array_keys($values)) . ') VALUES (';
        $sql .= implode(',', $values) . ') ON CONFLICT (';
        $sql .= implode(',', array_keys($wheres)) . ') DO UPDATE SET ';

        $updates = [];
        foreach ($values as $key => $val) {
            $updates[] = "{$key} = {$val}";
        }
        $sql .=  implode(',', $updates) . ' WHERE ';

        $wheres2 = [];
        foreach ($wheres as $key => $val) {
            $wheres2[] = "Z.{$key} = {$val}";
        }
        $sql .=  implode(' AND ', $wheres2);

        $stmt = $this->pdo->prepare($sql);
        $this->bind($stmt, $data);
        $this->bind($stmt, $where, ':_');
        $stmt->execute();
    }
}
