<?php

/**
*   ModelDbQueryTrait
*
*   @version 230927
*/

declare(strict_types=1);

namespace Concerto\standard\modeldbs;

use InvalidArgumentException;
use PDO;
use PDOStatement;
use Concerto\standard\DataModelInterface;

trait ModelDbQueryTrait
{
    /**
    *   @var string[]
    */
    protected array $order_clause = [
        'asc',
        'desc'
    ];

    /**
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
    *   select
    *
    *   @param DataModelInterface $where
    *   @param ?string $order
    *   @return DataModelInterface[]
    */
    public function select(
        DataModelInterface $where,
        ?string $order = null
    ): array {
        if (
            !is_null($order) &&
            !$this->isValidOrderClause($where, $order)
        ) {
            throw new InvalidArgumentException(
                "data type error:{$order}"
            );
        }

        $where_key = array_keys($where->toArray());

        $sql = "SELECT * FROM {$this->name} WHERE 1 = 1 ";

        if (!empty($where_key)) {
            foreach ($where->toArray() as $key => $val) {
                if (!is_null($val)) {
                    $sql .= "AND {$key} = :{$key} ";
                }
            }
        }

        if (!is_null($order)) {
            $sql .= " ORDER BY {$order}";
        }

        $stmt = $this->pdo->prepare($sql);
        $this->bind($stmt, $where);
        $stmt->execute();
        $stmt = $this->decorate($stmt, get_class($where));
        return (array)$stmt->fetchAll();
    }

    /**
    *   SELECT結果指定行取得
    *
    *   @param DataModelInterface $where
    *   @param ?string $order
    *   @param int $offset
    *   @return ?DataModelInterface
    */
    public function selectRow(
        DataModelInterface $where,
        ?string $order = null,
        int $offset = 0,
    ): ?DataModelInterface {
        $all_data = $this->select($where, $order);
        $result = array_slice((array)$all_data, $offset, 1);
        return empty($result) ? null : $result[0];
    }

    /**
    *   集約検索
    *
    *   @param string $select SELECT句
    *       集約後のエイリアス名はカラム名と同じにする
    *   @param DataModelInterface $where
    *   @param ?string $group
    *   @return DataModelInterface[]
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
    *   @param DataModelInterface $template 基準ModelData
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
            array_keys((array)$template->getInfo()),
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
    *   @param DataModelInterface $template 基準ModelData
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
    *   @param DataModelInterface $template 基準ModelData
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
