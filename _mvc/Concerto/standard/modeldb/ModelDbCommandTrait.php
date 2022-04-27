<?php

/**
*   ModelDbCommandTrait
*
*   @version 220209
*/

declare(strict_types=1);

namespace Concerto\standard\modeldb;

use InvalidArgumentException;
use PDO;
use PDOStatement;
use Concerto\standard\DataModelInterface;

trait ModelDbCommandTrait
{
    /**
    *   INSERT
    *
    *   @param ModelData[] $dataset [data,...]
    *   @return void
    *   @throws InvalidArgumentException
    */
    public function insert($dataset): void
    {
        if (!is_array($dataset)) {
            throw new InvalidArgumentException(
                "not Array"
            );
        }

        $schema_old = [];
        $count = 0;
        $stmt = null;
        $sql = '';
        $binds = [];
        $i = 0;

        foreach ($dataset as $obj) {
            if (!$obj instanceof DataModelInterface) {
                throw new InvalidArgumentException(
                    "data type different"
                );
            }

            if ($schema_old == array_keys($obj->toArray())) {
                $values = [];
                $i++;

                foreach ($obj->toArray() as $key => $val) {
                    if (!is_null($val)) {
                        $values[] = ":{$i}{$key}";
                    }
                }

                $sql .= sprintf(
                    " , (%s)",
                    implode(',', $values)
                );
                $binds[] = $obj;
            } else {
                if ($schema_old != []) {
                    $stmt = $this->pdo->prepare($sql);
                    $j = 0;

                    foreach ($binds as $obj1) {
                        $this->bind($stmt, $obj1, ":{$j}");
                        $j++;
                    }
                    $stmt->execute();
                }

                $fields = [];
                $values = [];
                $binds = [];
                $i = 0;

                foreach ($obj->toArray() as $key => $val) {
                    if (!is_null($val)) {
                        $fields[] = $key;
                        $values[] = ":{$i}{$key}";
                    }
                }

                $sql = sprintf(
                    "INSERT INTO %s (%s) VALUES (%s) ",
                    $this->name,
                    implode(',', $fields),
                    implode(',', $values)
                );
                $binds[] = $obj;
            }
            $schema_old = array_keys($obj->toArray());
            $count++;
        }

        if ($schema_old != []) {
            $stmt = $this->pdo->prepare($sql);
            $j = 0;

            foreach ($binds as $obj1) {
                $this->bind($stmt, $obj1, ":{$j}");
                $j++;
            }
            $stmt->execute();
        }
    }

    /**
    *   UPDATE
    *
    *   @param ModelData[][] $dataset [[data,where], ...]
    *   @return void
    *   @throws InvalidArgumentException
    */
    public function update($dataset): void
    {
        /**
        *   @var PDOStatement
        */
        static $stmt;

        /**
        *   データ前回値
        *
        *   @var string[]
        */
        static $data_old;

        /**
        *   WHERE前回値
        *
        *   @var DataModelInterface
        */
        static $where_old;

        /**
        *   データ前回値(php8.1対策)
        *
        *   @var DataModelInterface
        */
        static $data_obj_old;

        if (!is_array($dataset)) {
            throw new InvalidArgumentException(
                "not Array"
            );
        }

        $count = 0;
        $stmt = null;
        $sql = '';

        foreach ($dataset as $list) {
            if (!is_array($list) || count($list) != 2) {
                throw new InvalidArgumentException(
                    "inner array not Array"
                );
            }

            $obj = $list[0];
            $where = $list[1];

            if (
                !($obj instanceof DataModelInterface) ||
                !($where instanceof DataModelInterface) ||
                $obj::class !== $where::class
            ) {
                throw new InvalidArgumentException(
                    "data type different"
                );
            }

            $data_key = array_keys($obj->toArray());
            $where_key = array_keys($where->toArray());

            if (
                $data_key != $data_old ||
                $where_key != $where_old ||
                empty($stmt) ||
                $obj != $data_obj_old
            ) {
                $sql = "UPDATE {$this->name} SET ";

                foreach ($obj->toArray() as $key => $val) {
                    if (!is_null($val)) {
                        $sql .= " {$key} = :{$key},";
                    }
                }

                $sql = mb_substr($sql, 0, (mb_strlen($sql) - 1));

                if (!empty($where_key)) {
                    $sql .= ' WHERE 1 = 1 ';
                    foreach ($where->toArray() as $key => $val) {
                        if (!is_null($val)) {
                            $sql .= "AND {$key} = :_{$key} ";
                        }
                    }
                }

                $stmt = $this->pdo->prepare($sql);
                $data_old = $data_key;
                $where_old = $where_key;
                $data_obj_old = $obj;
            }

            $this->bind($stmt, $obj);
            $this->bind($stmt, $where, ':_');
            $stmt->execute();
            $count++;
        }
    }

    /**
    *   DELETE
    *
    *   @param ModelData[] $dataset [where, ...]
    *   @return void
    *   @throws InvalidArgumentException
    */
    public function delete($dataset): void
    {
        if (!is_array($dataset)) {
            throw new InvalidArgumentException(
                "not Array"
            );
        }

        $sql = "DELETE FROM {$this->name} ";
        $flg = false;
        $binds = [];
        $i = 0;

        foreach ($dataset as $obj) {
            if (!$obj instanceof DataModelInterface) {
                throw new InvalidArgumentException(
                    "data type different"
                );
            }

            if (!$flg) {
                $sql .= 'WHERE ';
                $flg = true;
            } else {
                $sql .= 'OR ';
            }

            $fields = [];

            foreach ($obj->toArray() as $key => $val) {
                if (!is_null($val)) {
                    $fields[] = "{$key} = :{$i}{$key}";
                }
            }

            $sql .= sprintf("(%s) ", implode(' AND ', $fields));
            $binds[] = $obj;
            $i++;
        }

        $stmt = $this->pdo->prepare($sql);
        $j = 0;

        foreach ($binds as $obj1) {
            $this->bind($stmt, $obj1, ":{$j}");
            $j++;
        }
        $stmt->execute();
    }

    /**
    *   TRUNCATE
    *
    */
    public function truncate(): void
    {
        $sql = "truncate {$this->name} CASCADE";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }
}
