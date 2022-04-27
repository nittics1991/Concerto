<?php

/**
*   claim_inf
*
*   @version 210903
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\database\ClaimInfData;
use Concerto\standard\ModelData;
use Concerto\Validate;

class ClaimBunruiData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'no_claim' => parent::STRING,
        'kb_syubetu' => parent::STRING,
        'kb_genin' => parent::STRING,
        'kb_jyudaido' => parent::STRING,
        'nm_bunrui' => parent::STRING,
    ];

    /**
    *   Column Alias
    *
    *   @var array
    */
    protected static $alias = [];

    /**
    *   種別
    *
    *   @var array
    */
    protected $syubetu = [
        0 => 'トラブル',
        1 => '(改善)要望',
        2 => '問合せ',
        3 => 'その他'
    ];

    /**
    *   原因
    *
    *   @var array
    */
    protected $genin = [
        'A' => '外部仕様',
        'B' => '内部仕様',
        'C' => 'ソフトウェア設計',
        'D' => 'ソースコード',
        'E' => 'マニュアル',
        'F' => '誤修正',
        'G' => 'テストケース',
        'H' => 'ハードウェア',
        'W' => '誤操作･誤運用',
        'X' => '保留･不明･他',
    ];

    /**
    *   重大度
    *
    *   @var array
    */
    protected $jyudaido = [
        0 => '重大',
        1 => '重要',
        2 => '軽微',
        3 => '外見的',
    ];

    /**
    *   費用
    *
    *   @var array
    */
    protected $hiyo = [
        0 => '有償',
        1 => '無償',
    ];

    /**
    *   種別
    *
    *   @param string|null $key
    *   @return string|array
    */
    public function getSyubetu($key = null)
    {
        if (is_null($key)) {
            return $this->syubetu;
        }
        if (array_key_exists($key, $this->syubetu)) {
            return $this->syubetu[$key];
        }
        return;
    }

    /**
    *   原因
    *
    *   @param string|null $key
    *   @return string|array
    */
    public function getGenin($key = null)
    {
        if (is_null($key)) {
            return $this->genin;
        }
        if (array_key_exists($key, $this->genin)) {
            return $this->genin[$key];
        }
        return;
    }

    /**
    *   重大度
    *
    *   @param string|null $key
    *   @return string|array
    */
    public function getJyudaido($key = null)
    {
        if (is_null($key)) {
            return $this->jyudaido;
        }
        if (array_key_exists($key, $this->jyudaido)) {
            return $this->jyudaido[$key];
        }
        return;
    }

    /**
    *   費用
    *
    *   @param string|null $key
    *   @return string|array|null
    */
    public function getHiyo($key = null)
    {
        if (is_null($key)) {
            return $this->hiyo;
        }
        if (array_key_exists($key, $this->hiyo)) {
            return $this->hiyo[$key];
        }
        return;
    }

    public function isValidNo_claim($val)
    {
        return ClaimInfData::isValidNo_claim($val);
    }

    public function isValidKb_syubetu($val)
    {
        return Validate::isTextInt($val, 0, 4);
    }

    public function isValidKb_genin($val)
    {
        return Validate::isText($val, 1, 1) &&
            mb_ereg_match('\A[A-Z]\z');
    }

    public function isValidKb_jyudaido($val)
    {
        return Validate::isTextInt($val, 0, 4);
    }

    public function isValidNm_bunrui($val)
    {
        return Validate::isTextEscape($val);
    }
}
