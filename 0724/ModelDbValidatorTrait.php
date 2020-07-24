<?php

/**
*   ModelDb Validator Trait
*
*   @version 200724
*/

declare(strict_types=1);

namespace Concerto\sql;

use Concerto\standard\DataModelInterface;

trait ModelDbValidatorTrait
{
    /**
    *   ORDER句許可文字
    *
    *   @var array
    */
    protected $order_keyword = [
        'asc',
        'desc'
    ];
    
    /**
    *   SQL句バリデーション
    *
    *   @param array $keyword 許可文字列
    *   @param DataModelInterface $columns カラム定義
    *   @param string $token 判定対象文字列
    *   @return bool
    */
    protected function isValidToken(
        array $keyword,
        DataModelInterface $columns,
        ?string $token,
    ) {
        if (is_null($token)) {
            return true;
        }
        
        //$pattern[]表現[ ,　]で全角空白エラーする
        $ar = mb_split('( |,|　|\(|\))', mb_strtolower($token));
        
        if (empty($ar)) {
            return true;
        }
        
        $allows = array_merge(
            ['', '　'],
            array_keys($columns->getInfo()),
            $keyword
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
    *   @param DataModelInterface $columns カラム定義
    *   @param string $token
    *   @return bool
    */
    protected function isValidOrderToken(
        DataModelInterface $columns,
        string $token
    ): bool {
        return $this->isValidToken(
            $this->order_keyword
            $columns,
            $token
        );
    }
}
