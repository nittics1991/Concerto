<?php

/**
*   claim_inf
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class ClaimInfData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'no_claim' => parent::STRING,
        'kb_nendo' => parent::STRING,
        'cd_bumon' => parent::STRING,
        'no_cyu' => parent::STRING,
        'no_cyu_zn' => parent::STRING,
        'nm_user' => parent::STRING,
        'nm_system' => parent::STRING,
        'cd_tanto' => parent::STRING,
        'nm_renraku' => parent::STRING,
        'dt_hassei' => parent::STRING,
        'nm_mondai' => parent::STRING,
        'nm_keika' => parent::STRING,
        'nm_genin' => parent::STRING,
        'nm_taisaku' => parent::STRING,
        'nm_saihatu' => parent::STRING,
        'dt_kaisyu' => parent::STRING,
        'nm_doc' => parent::STRING,
        'nm_pro' => parent::STRING,
        'dt_pkanryo' => parent::STRING,
        'dt_kakunin' => parent::STRING,
        'nm_kakunin' => parent::STRING,
        'nm_biko' => parent::STRING,
        'nm_tyosa' => parent::STRING,
        'nm_syonin' => parent::STRING,
        'ins_date' => parent::STRING,
        'update' => parent::STRING,
        'editor' => parent::STRING,
    ];

    /**
    *   @var string[]
    */
    protected array $syubetu = [
        'トラブル', '(改善)要望','問合せ','その他'
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
        'X' => '保留･不明･他'
    ];

    /**
    *   @var string[]
    */
    protected array $jyudaido = [
        '重大', '重要','軽微','外見的'
    ];

    /**
    *   @var string[]
    */
    protected array $hiyou = [
        '有償', '無償'
    ];

    /**
    *   種別
    *
    *   @param ?string $code
    *   @return null|string|string[]
    */
    public function getSyubetu(
        ?string $code = null,
    ): null|string|array {
        if ($code === null) {
            return $this->syubetu;
        }
        return $this->syubetu[$code] ?? null;
    }

    /**
    *   原因
    *
    *   @param ?string $code
    *   @return null|string|string[]
    */
    public function getGennin(
        ?string $code = null,
    ): null|string|array {
        if ($code === null) {
            return $this->gennin;
        }
        return $this->gennin[$code] ?? null;
    }

    /**
    *   重大度
    *
    *   @param ?string $code
    *   @return null|string|string[]
    */
    public function getJyudaido(
        ?string $code = null,
    ): null|string|array {
        if ($code === null) {
            return $this->jyudaido;
        }
        return $this->jyudaido[$code] ?? null;
    }

    /**
    *   費用
    *
    *   @param ?string $code
    *   @return null|string|string[]
    */
    public function getHiyou(
        ?string $code = null,
    ): null|string|array {
        if ($code === null) {
            return $this->hiyou;
        }
        return $this->hiyou[$code] ?? null;
    }

    public function isValidNo_claim(
        mixed $val
    ): bool {
        return Validate::isCyuban($val);
    }

    public function isValidKb_nendo(
        mixed $val
    ): bool {
        return Validate::isNendo($val);
    }

    public function isValidCd_bumon(
        mixed $val
    ): bool {
        return Validate::isBumon($val);
    }

    public function isValidNo_cyu(
        mixed $val
    ): bool {
        return Validate::isCyuban($val);
    }

    public function isValidNo_cyu_zn(
        mixed $val
    ): bool {
        return Validate::isCyuban($val);
    }

    public function isValidNm_user(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val);
    }

    public function isValidNm_system(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val);
    }

    public function isValidCd_tanto(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }

    public function isValidNm_renraku(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val);
    }

    public function isValidDt_hassei(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidNm_mondai(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val);
    }

    public function isValidNm_keika(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val);
    }

    public function isValidNm_genin(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val);
    }

    public function isValidNm_taisaku(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val);
    }

    public function isValidNm_saihatu(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val);
    }

    public function isValidDt_kaisyu(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidNm_doc(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val);
    }

    public function isValidNm_pro(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val);
    }

    public function isValidDt_pkanryu(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidDt_kakunin(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidNm_kakunin(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val);
    }

    public function isValidNm_biko(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val);
    }

    public function isValidNm_tyousa(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val);
    }

    public function isValidNm_syonin(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val);
    }

    public function isValidIns_date(
        mixed $val
    ): bool {
        return Validate::isTextDateTime($val);
    }

    public function isValidUpdate(
        mixed $val
    ): bool {
        return Validate::isTextDateTime($val);
    }

    public function isValidEditor(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }
}
