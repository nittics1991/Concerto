<?php

/**
*   operation_hist
*
*   @version 240220
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

/**
*   @extends ModelData<string|int>
*/
class OperationHistData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'ins_date' => parent::STRING,
        'cd_tanto' => parent::STRING,
        'nm_before' => parent::STRING,
        'nm_after' => parent::STRING,
        'nm_table' => parent::STRING,
        'no_cyu' => parent::STRING,
        'no_page' => parent::INTEGER,
    ];

    /**
    *   @var string[]
    */
    private array $nm_table_list = [
        1 => '注入計画',
        3 => 'ワークフロー',
        4 => 'クレーム',
        5 => 'プロスペクト',
        8 => 'プロジェクト',
        9 => '調達項目',
        11 => '見積台帳',
        12 => '非直課管理',
    ];

    public function isValidIns_date(
        mixed $val
    ): bool {
        return Validate::isTextDateTime($val);
    }

    public function isValidCd_tanto(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }

    //nm_before
    //nm_after

    public function isValidNm_table(
        mixed $val
    ): bool {
        return
            (is_int($val) || is_string($val)) &&
            array_key_exists(
                intval($val),
                $this->nm_table_list,
            );
    }

    public function isValidNo_cyu(
        mixed $val
    ): bool {
        return Validate::isCyuban($val);
    }

    public function isValidNo_page(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    /**
    *   getTableName
    *
    *   @param ?int $id
    *   @return null|string|string[]
    */
    public function getTableName(
        ?int $id = null
    ): null|string|array {
        if (!isset($id)) {
            return $this->nm_table_list;
        }

        return (array_key_exists($id, $this->nm_table_list)) ?
            $this->nm_table_list[$id] : null;
    }
}
