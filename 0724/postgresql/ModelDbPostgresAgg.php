<?php

/**
*   ModelDb 集約SQL Trait (postgresql)
*
*   @version 200724
*/

declare(strict_types=1);

namespace Concerto\sql;

use Concerto\standard\DataModelInterface;

class ModelDbAggPostgresTrait implements ModelDbFunctionInterface
{
    /**
    *   WINDOW OVER句許可文字
    *
    *   @var array
    */
    protected $window_keyword = [
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
    *   @var array
    */
    protected $agg_function_keyword = [
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
    *   集約句バリデーション
    *
    *   @param DataModelInterface $columns カラム定義
    *   @param string $token
    *   @return bool
    */
    protected function isValidAggToken(
        DataModelInterface $columns,
        string $token
    ): bool {
        $keywords = array_merge(
            $this->window_keyword,
            $this->agg_function_keyword
        );
        return $this->isValidToken(
            $keywords,
            $columns,
            $token
        );
    }
    
    
    
    
    
    
    
    
    
    /**
    *   集約検索
    *
    *   @param string $select SELECT句(集約後のエイリアス名はカラム名と同じにする)
    *   @param DataModelInterface $where WHERE条件
    *   @param ?string $group GROUP BY句
    *   @return array ModelData結果セット
    *   @throws InvalidArgumentException
    *   @example $where->id $group='year, month' $select='SUM(money) AS money'
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
        
        if (!is_null($group) && (!$this->isValidClause($where, $group))) {
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
}
