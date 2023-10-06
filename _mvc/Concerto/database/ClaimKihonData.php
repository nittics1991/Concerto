<?php

/**
*   claim_kihon
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class ClaimKihonData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
    ];

    /**
    *   @var string[]
    */
    protected array $syubetu = [
        0 => 'トラブル',
        1 => '(改善)要望',
        2 => '問合せ',
        3 => 'その他'
    ];

    /**
    *   @var string[]
    */
    protected array $gennin = [
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
    *   @var string[]
    */
    protected array $jyudaido = [
        0 => '重大',
        1 => '重要',
        2 => '軽微',
        3 => '外見的',
    ];

    /**
    *   @var string[]
    */
    protected array $hiyo = [
        0 => '有償',
        1 => '無償',
    ];

    /**
    *   種別
    *
    *   @param ?string $key
    *   @return null|string|string[]
    */
    public function getSyubetu(
        ?string $key = null
    ): null|string|array {
        if (is_null($key)) {
            return $this->syubetu;
        }
        if (array_key_exists($key, $this->syubetu)) {
            return $this->syubetu[$key];
        }
        return null;
    }

    /**
    *   原因
    *
    *   @param ?string $key
    *   @return null|string|string[]
    */
    public function getGennin(
        ?string $key = null
    ): null|string|array {
        if (is_null($key)) {
            return $this->gennin;
        }
        if (array_key_exists($key, $this->gennin)) {
            return $this->gennin[$key];
        }
        return null;
    }

    /**
    *   重大度
    *
    *   @param ?string $key
    *   @return null|string|string[]
    */
    public function getJyudaido(
        ?string $key = null
    ): null|string|array {
        if (is_null($key)) {
            return $this->jyudaido;
        }
        if (array_key_exists($key, $this->jyudaido)) {
            return $this->jyudaido[$key];
        }
        return null;
    }

    /**
    *   費用
    *
    *   @param ?string $key
    *   @return null|string|string[]
    */
    public function getHiyo(
        ?string $key = null
    ): null|string|array {
        if (is_null($key)) {
            return $this->hiyo;
        }
        if (array_key_exists($key, $this->hiyo)) {
            return $this->hiyo[$key];
        }
        return null;
    }
}
