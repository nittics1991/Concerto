<?php

/**
*   claim_kihon
*
*   @version 180808
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class ClaimKihonData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
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
    protected $gennin = [
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
    public function getGennin($key = null)
    {
        if (is_null($key)) {
            return $this->gennin;
        }
        if (array_key_exists($key, $this->gennin)) {
            return $this->gennin[$key];
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
}
