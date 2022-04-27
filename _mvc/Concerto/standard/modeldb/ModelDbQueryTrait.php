<?php

/**
*   ModelDbQueryTrait
*
*   @version 220209
*/

declare(strict_types=1);

namespace Concerto\standard\modeldb;

use InvalidArgumentException;
use PDO;
use PDOStatement;
use Concerto\standard\DataModelInterface;

trait ModelDbQueryTrait
{
    /**
    *   ORDER句許可文字
    *
    *   @var string[]
    */
    protected array $order_clause = [
        'asc',
        'desc'
    ];

    /**
    *   WINDOW OVER句許可文字
    *
    *   @var string[]
    */
    protected array $window_clause = [
        'asc',
        'desc',
        'as',
        'over',
        'partition',
        'order',
        'by'
    ];

    /**
    *   集約関数許可文字
    *
    *   @var string[]
    */
    protected array $agg_function = [
        'array_agg',
        'avg',
        'bit_and',
        'bit_or',
        'bool_and',
        'bool_or',
        'count',
        'every',
        'json_agg',
        'max',
        'min',
        'sum',
        'xml_agg',
        'row_number',
        'rank',
        'dense_rank',
        'percent_rank',
        'cume_dist',
        'first_value',
        'last_value'
    ];

    /**
    *   SELECT
    *
    *   @param DataModelInterface $obj WHERE条件
    *   @param ?string $order ORDER句
    *   @return DataModelInterface[] 結果セットクラス
    *   @throws InvalidArgumentException
    */
    public function select(
        DataModelInterface $obj,
        ?string $order = null
    ) {
        if (
            !is_null($order) &&
            !$this->isValidOrderClause($obj, $order)
        ) {
            throw new InvalidArgumentException(
                "data type error:{$order}"
            );
        }

        $where_key = array_keys($obj->toArray());

        $sql = "SELECT * FROM {$this->name} WHERE 1 = 1 ";

        if (!empty($where_key)) {
            foreach ($obj->toArray() as $key => $val) {
                if (!is_null($val)) {
                    $sql .= "AND {$key} = :{$key} ";
                }
            }
        }

        if (!is_null($order)) {
            $sql .= " ORDER BY {$order}";
        }

        $stmt = $this->pdo->prepare($sql);
        $this->bind($stmt, $obj);
        $stmt->execute();
        $stmt = $this->decorate($stmt, get_class($obj));
        return (array)$stmt->fetchAll();
    }

    /**
    *   SELECT結果指定行取得
    *
    *   @param DataModelInterface $obj WHERE条件
    *   @param ?string $order ORDER句
    *   @param ?int $offset オフセット
    *   @return ?DataModelInterface 結果セットクラス
    *   @throws InvalidArgumentException
    */
    public function selectRow(
        DataModelInterface $obj,
        ?string $order = null,
        ?int $offset = 0,
    ): ?DataModelInterface {
        $all_data = $this->select($obj, $order);
        $result = array_slice((array)$all_data, $offset, 1);
        return empty($result) ? null : $result[0];
    }

    /**
    *   集約検索
    *
    *   @param string $select SELECT句(集約後のエイリアス名はカラム名と同じにする)
    *   @param DataModelInterface $where WHERE条件
    *   @param ?string $group GROUP BY句
    *   @return DataModelInterface[] 結果セット
    *   @throws InvalidArgumentException
    *   @example $where->id $group='year, month'
    *           $select='SUM(money) AS money'
    */
    public function groupBy(
        string $select,
        DataModelInterface $where,
        ?string $group = null
    ): array {
        $class_name = $this->entityName();

        if (!($where instanceof $class_name)) {
            throw new InvalidArgumentException(
                "data type error:{$class_name}"
            );
        }

        if (
            !is_null($group) &&
            !$this->isValidClause($where, $group)
        ) {
            throw new InvalidArgumentException(
                "data type error:{$group}"
            );
        }

        if (!$this->isValidAggClause($where, $select)) {
            throw new InvalidArgumentException(
                "data type error:{$select}"
            );
        }

        $sql = "
            SELECT {$select} 
            FROM {$this->name} 
            WHERE 1 = 1 
        ";

        foreach ($where->toArray() as $key => $val) {
            if (!is_null($val)) {
                $sql .= "AND {$key} = :{$key} ";
            }
        }

        if (is_string($group)) {
            $sql .= "
                GROUP BY {$group} 
            ";
        }

        $stmt = $this->pdo->prepare($sql);
        $this->bind($stmt, $where);
        $stmt = $this->decorate($stmt, get_class($where));
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }

    /**
    *   SQL句バリデーション
    *
    *   @param DataModelInterface $template 基準ModelData class
    *   @param string $clause 判定対象文字列
    *   @param string[] $haystack 許可文字列
    *   @return bool
    *   @see ModelDataのプロパティまたは許可句のみがtrue
    */
    protected function isValidClause(
        DataModelInterface $template,
        ?string $clause,
        array $haystack = []
    ): bool {
        if (is_null($clause)) {
            return true;
        }

        //$pattern[]表現[ ,　]で全角空白エラーする
        $ar = mb_split('( |,|　|\(|\))', mb_strtolower($clause));

        if (empty($ar)) {
            return true;
        }

        $allows = array_merge(
            ['', '　'],
            array_keys($template->getInfo()),
            $haystack
        );

        foreach ($ar as $val) {
            if (!in_array($val, $allows)) {
                return false;
            }
        }
        return true;
    }

    /**
    *   ORDER句バリデーション
    *
    *   @param DataModelInterface $template 基準ModelData class
    *   @param string $clause 判定対象文字列
    *   @return bool
    */
    protected function isValidOrderClause(
        DataModelInterface $template,
        string $clause
    ): bool {
        return $this->isValidClause(
            $template,
            $clause,
            $this->order_clause
        );
    }

    /**
    *   集約句バリデーション
    *
    *   @param DataModelInterface $template 基準ModelData class
    *   @param string $clause 判定対象文字列
    *   @return bool
    */
    protected function isValidAggClause(
        DataModelInterface $template,
        string $clause
    ): bool {
        $haystack = array_merge(
            $this->window_clause,
            $this->agg_function
        );
        return $this->isValidClause($template, $clause, $haystack);
    }
}
