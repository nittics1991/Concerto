<?php

/**
*   claim_inf
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\database\ClaimInfData;
use Concerto\standard\ModelData;
use Concerto\Validate;

class ClaimBunruiData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'no_claim' => parent::STRING,
        'kb_syubetu' => parent::STRING,
        'kb_genin' => parent::STRING,
        'kb_jyudaido' => parent::STRING,
        'nm_bunrui' => parent::STRING,
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
    protected array $genin = [
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
    public function getGenin(
        ?string $key = null
    ): null|string|array {
        if (is_null($key)) {
            return $this->genin;
        }
        if (array_key_exists($key, $this->genin)) {
            return $this->genin[$key];
        }
        return null;
    }

    /**
    *   重大度
    *
    *   @param string|null $key
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
    *   @param string|null $key
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

    public function isValidNo_claim(
        mixed $val
    ): bool {
        return ClaimInfData::isValidNo_claim($val);
    }

    public function isValidKb_syubetu(
        mixed $val
    ): bool {
        return Validate::isTextInt($val, 0, 4);
    }

    public function isValidKb_genin(
        mixed $val
    ): bool {
        return Validate::isText($val, 1, 1) &&
            mb_ereg_match('\A[A-Z]\z', strval($val));
    }

    public function isValidKb_jyudaido(
        mixed $val
    ): bool {
        return Validate::isTextInt($val, 0, 4);
    }

    public function isValidNm_bunrui(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val);
    }
}
