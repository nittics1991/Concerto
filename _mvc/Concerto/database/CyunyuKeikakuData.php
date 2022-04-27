<?php

/**
*   cyunyu_keikaku
*
*   @version 220214
*/

declare(strict_types=1);

namespace Concerto\database;

use InvalidArgumentException;
use Concerto\standard\ModelData;
use Concerto\Validate;

class CyunyuKeikakuData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        "update" => parent::STRING
        , "editor" => parent::STRING
        , "no_id" => parent::INTEGER
        , "no_cyu" => parent::STRING
        , "no_ko" => parent::STRING
        , "dt_kanjyo" => parent::STRING
        , "cd_genka_yoso" => parent::STRING
        , "cd_bumon" => parent::STRING
        , "nm_cyunyu" => parent::STRING
        , "nm_syohin" => parent::STRING
        , "tm_cyokka" => parent::DOUBLE
        , "yn_money" => parent::INTEGER
    ];

    /**
    *   非連想配列一括入力用対応テーブル
    *
    *   @var array
    */
    protected static $number_table = [
        0 => 'update',
        1 => 'editor',
        2 => "no_id",
        3 => "no_cyu",
        4 => "no_ko",
        5 => "dt_kanjyo",
        6 => "cd_genka_yoso",
        7 => "cd_bumon",
        8 => "nm_cyunyu",
        9 => "nm_syohin",
        10 => "tm_cyokka",
        11 => "yn_money"
    ];

    /**
    *   数値配列一括入力
    *
    *   @param array $array
    *   @throws InvalidArgumentException
    */
    public function fromNumberArray(array $array)
    {
        foreach ($array as $key => $val) {
            if (!array_key_exists($key, static::$number_table)) {
                throw new InvalidArgumentException(
                    "array key not exists:{$key}_{$val}"
                );
            }

            $prop = static::$number_table[$key];
            $type = static::$schema[$prop];

            switch ($type) {
                case parent::INTEGER:
                    $data = intval(
                        filter_var($val, FILTER_SANITIZE_NUMBER_INT)
                    );
                    break;
                case parent::DOUBLE:
                    $data = floatval(
                        filter_var(
                            $val,
                            FILTER_SANITIZE_NUMBER_FLOAT,
                            FILTER_FLAG_ALLOW_FRACTION,
                        )
                    );
                    break;
                default:
                    $data = $val;
            }
            $this->data[$prop] = $data;
        }
    }

    /**
    *   数値配列一括出力
    *
    *   @return array
    */
    public function toNumberArray()
    {
        $tables = static::$number_table;
        ksort($tables, SORT_NUMERIC);
        $items = [];

        foreach ($tables as $val) {
            $items[] = $this->data[$val];
        }
        return $items;
    }

    public function isValidUpdate($val)
    {
        return Validate::isTextDateTime($val);
    }

    public function isValidEditor($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidNo_id($val)
    {
        return Validate::isInt($val, 0, 1000);
    }

    public function isValidNo_cyu($val)
    {
        return Validate::isCyuban($val);
    }

    public function isValidNo_ko($val)
    {
        return Validate::isKoban($val);
    }

    public function isValidDt_kanjyo($val)
    {
        return Validate::isTextDateYYYYMM($val);
    }

    public function isValidCd_genka_yoso($val)
    {
        return Validate::isGenkaYoso($val);
    }

    public function isValidCd_bumon($val)
    {
        return Validate::isBumon($val);
    }

    public function isValidNm_cyunyu($val)
    {
        if ($this->cd_genka_yoso == 'C1') {
            return Validate::isTanto($val);
        }
        return Validate::isTextEscape($val, 0, 100) &&
            !Validate::hasTextHankaku($val) &&
            !Validate::hasTextHtml($val) &&
            !Validate::hasTextDatabase($val);
    }

    public function isValidNm_syohin($val)
    {
        if ($val == '') {
            return true;
        }
        return Validate::isTextEscape($val, 0, 100) &&
            !Validate::hasTextHankaku($val) &&
            !Validate::hasTextHtml($val) &&
            !Validate::hasTextDatabase($val);
    }

    public function isValidTm_cyokka($val)
    {
        return Validate::isDouble($val);
    }

    public function isValidYn_money($val)
    {
        return Validate::isInt($val);
    }
}
