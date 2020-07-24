<?php

/**
*   ModelDb Validator Trait (postgresql)
*
*   @version 200724
*/

declare(strict_types=1);

namespace Concerto\sql;

use Concerto\standard\DataModelInterface;

trait ModelDbValidatorPostgresTrait
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
    
}
