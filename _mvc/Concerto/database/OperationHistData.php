<?php

/**
*   operation_hist
*
*   @version 200904
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class OperationHistData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        "ins_date" => parent::STRING
        , "cd_tanto" => parent::STRING
        , "nm_before" => parent::STRING
        , "nm_after" => parent::STRING
        , "nm_table" => parent::STRING
        , "no_cyu" => parent::STRING
        , "kb_nendo" => parent::STRING
        , "no_seq" => parent::STRING
    ];

    /**
    *   tableName
    *
    *   @var array
    */
    private $nm_table_list = [
        1 => '注入計画', 3 => 'ワークフロー',
        4 => 'クレーム', 5 => 'プロスペクト', 6 => '設備予約',
        7 => '購入払出', 8 => 'プロジェクト', 9 => '調達項目',
        10 => 'アドレス帳', 11 => '見積台帳',
        101 => 'マスタ部門', 102 => 'マスタ担当',103 => 'マスタスキル'
    ];

    public function isValidIns_date($val)
    {
        return Validate::isTextDateTime($val);
    }

    public function isValidCd_tanto($val)
    {
        return Validate::isTanto($val);
    }

    //nm_before
    //nm_after

    public function isValidNm_table($val)
    {
        return Validate::isTextInt($val, 1, 7);
    }

    public function isValidNo_cyu($val)
    {
        return Validate::isCyuban($val);
    }

    public function isValidKb_nendo($val)
    {
        return Validate::isNendo($val);
    }

    public function isValidNo_Seq($val)
    {
        return Validate::isTextInt($val, 0);
    }

    /**
    *   getTableName
    *
    *   @param int|null $id
    *   @return string|array|null
    */
    public function getTableName($id = null)
    {
        if (!isset($id)) {
            return $this->nm_table_list;
        }

        return (array_key_exists($id, $this->nm_table_list)) ?
            $this->nm_table_list[$id] : null;
    }
}
