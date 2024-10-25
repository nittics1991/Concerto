<?php

/**
*   claim_inf
*
*   @version 231101
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

/**
*   @extends ModelData<string|int|float>
*/
class ClaimInfData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'no_claim' => parent::STRING,
        'kb_nendo' => parent::STRING,
        'no_seq' => parent::INTEGER,
        'cd_bumon' => parent::STRING,
        'cd_tanto' => parent::STRING,
        'nm_site' => parent::STRING,
        'dt_hassei' => parent::STRING,
        'nm_renraku' => parent::STRING,
        'nm_bunrui' => parent::STRING,
        'kb_syubetu' => parent::STRING,
        'kb_jyudaido' => parent::STRING,
        'kb_gennin' => parent::STRING,
        'dt_kaisyu' => parent::STRING,
        'nm_kaisyu' => parent::STRING,
        'nm_doc' => parent::STRING,
        'nm_program' => parent::STRING,
        'dt_kakunin' => parent::STRING,
        'nm_kakunin' => parent::STRING,
        'kb_hiyou' => parent::STRING,
        'no_hosyu' => parent::STRING,
        'yn_keihi' => parent::INTEGER,
        'yn_tanka' => parent::INTEGER,
        'tm_cyokka' => parent::FLOAT,
        'nm_mondai' => parent::STRING,
        'nm_keika' => parent::STRING,
        'nm_gennin' => parent::STRING,
        'nm_taisaku' => parent::STRING,
        'nm_saihatu' => parent::STRING,
        'nm_biko' => parent::STRING,
        'ins_date' => parent::STRING,
        'up_date' => parent::STRING,
        'dt_end' => parent::STRING,
        'nm_tyosa' => parent::STRING,
        'nm_syonin' => parent::STRING,
    ];

    /**
    *   @var string[]
    */
    protected array $syubetu = [
        '0' => 'トラブル',
        '1' => '(改善)要望',
        '2' => '問合せ',
        '3' => 'その他',
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
        '0' => '重大',
        '1' => '重要',
        '2' => '軽微',
        '3' => '外見的'
    ];

    /**
    *   @var string[]
    */
    protected array $hiyou = [
        '0' => '有償',
        '1' => '無償'
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
        return is_string($val) &&
            mb_ereg_match(
                '\ACLM[0-9]{2}(K|S)[0-9]{3}\z',
                $val
            );
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

    public function isValidNo_seq(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidCd_tanto(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }

    public function isValidNm_site(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val);
    }

    public function isValidDt_hassei(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidNm_renraku(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val);
    }

    public function isValidNm_bunrui(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val);
    }

    public function isValidKb_syubetu(
        mixed $val
    ): bool {
        return is_string($val) &&
            array_key_exists(
                $val,
                $this->syubetu,
            );
    }

    public function isValidKb_jyudaido(
        mixed $val
    ): bool {
        return is_string($val) &&
            array_key_exists(
                $val,
                $this->jyudaido,
            );
    }

    public function isValidKb_gennin(
        mixed $val
    ): bool {
        return is_string($val) &&
            array_key_exists(
                $val,
                $this->gennin,
            );
    }

    public function isValidDt_kansyu(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidNm_kaisyu(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val);
    }

    public function isValidNm_doc(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val);
    }

    public function isValidNm_program(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val);
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

    public function isValidKb_hiyou(
        mixed $val
    ): bool {
        return is_string($val) &&
            array_key_exists(
                $val,
                $this->hiyou,
            );
    }

    public function isValidNo_hosyu(
        mixed $val
    ): bool {
        return Validate::isCyuban($val);
    }

    public function isValidYn_keihi(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidYn_tanka(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidTm_cyokka(
        mixed $val
    ): bool {
        return Validate::isFloat($val, 0.0);
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

    public function isValidNm_gennin(
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

    public function isValidNm_biko(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val);
    }

    public function isValidIns_date(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidUp_date(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidDt_end(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidNm_tyosa(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val);
    }

    public function isValidNm_syonin(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val);
    }
}
