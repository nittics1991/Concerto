<?php

/**
*   ModelDbCommandTrait
*
*   @version 221214
*/

declare(strict_types=1);

namespace Concerto\standard\modeldbs;

use InvalidArgumentException;
use PDO;
use PDOStatement;
use Concerto\standard\DataModelInterface;

trait ModelDbCommandTrait
{
    /**
    *   INSERT
    *
    *   @param DataModelInterface[] $dataset [data,...]
    *   @return void
    */
    public function insert(
        array $dataset
    ): void {
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
                if ($schema_old !== []) {
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

        if ($schema_old !== []) {
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
    *   @param DataModelInterface[][] $dataset [[data,where], ...]
    *   @return void
    */
    public function update(
        array $dataset
    ): void {
        /**
        *   @var PDOStatement
        */
        static $stmt;

        /**
        *   @var string[]
        */
        static $data_old;

        /**
        *   @var DataModelInterface
        */
        static $where_old;

        /**
        *   @var DataModelInterface
        */
        static $data_obj_old;

        $count = 0;
        $stmt = null;
        $sql = '';

        foreach ($dataset as $list) {
            if (!is_array($list) || count($list) !== 2) {
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
    *   @param DataModelInterface[] $dataset [where, ...]
    *   @return void
    */
    public function delete(
        array $dataset
    ): void {
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
    *   @return void
    */
    public function truncate(): void
    {
        $sql = "truncate {$this->name} CASCADE";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }
}
