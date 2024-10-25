<?php

/**
*   wf_new
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use InvalidArgumentException;
use Concerto\standard\ModelData;
use Concerto\Validate;

/**
*   @extends ModelData<string|int>
*/
class WfNewData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'kb_nendo' => parent::STRING,
        'no_cyu' => parent::STRING,
        'cd_bumon' => parent::STRING,
        'dt_dra_p' => parent::STRING,
        'dt_dra_r' => parent::STRING,
        'dt_drb_p' => parent::STRING,
        'dt_drb_r' => parent::STRING,
        'dt_drc_p' => parent::STRING,
        'dt_drc_r' => parent::STRING,
        'dt_drd_p' => parent::STRING,
        'dt_drd_r' => parent::STRING,
        'dt_dre_p' => parent::STRING,
        'dt_dre_r' => parent::STRING,
        'dt_drf_p' => parent::STRING,
        'dt_drf_r' => parent::STRING,
        'dt_drg_p' => parent::STRING,
        'dt_drg_r' => parent::STRING,
        'dt_drh_p' => parent::STRING,
        'dt_drh_r' => parent::STRING,
        'dt_drq_p' => parent::STRING,
        'dt_drq_r' => parent::STRING,
        'dt_drsi_p' => parent::STRING,
        'dt_drsi_r' => parent::STRING,
        'dt_drst_p' => parent::STRING,
        'dt_drst_r' => parent::STRING,
        'dt_syukka_p' => parent::STRING,
        'dt_syukka_r' => parent::STRING,
        'dt_gentis_p' => parent::STRING,
        'dt_gentis_r' => parent::STRING,
        'dt_gentie_p' => parent::STRING,
        'dt_gentie_r' => parent::STRING,
        'dt_cpi_p' => parent::STRING,
        'dt_cpi_r' => parent::STRING,
        'dt_cpt_p' => parent::STRING,
        'dt_cpt_r' => parent::STRING,
        'dt_doc_p' => parent::STRING,
        'dt_doc_r' => parent::STRING,
        'no_cyu_t' => parent::STRING,
        'cd_tanto' => parent::STRING,
        'no_tm_wt' => parent::FLOAT,
        'nm_cd_syukka' => parent::STRING,
        'dt_zanken_s_p' => parent::STRING,
        'dt_zanken_s_r' => parent::STRING,
        'nm_zanken_s' => parent::STRING,
        'dt_zanken_g_p' => parent::STRING,
        'dt_zanken_g_r' => parent::STRING,
        'nm_zanken_g' => parent::STRING,
        'no_zanken_s' => parent::INTEGER,
        'no_zanken_g' => parent::INTEGER,
        'dt_koutei_p' => parent::STRING,
        'dt_koutei_r' => parent::STRING,
        'dt_cyotatu_p' => parent::STRING,
        'dt_cyotatu_r' => parent::STRING,
        'dt_cyunyu_p' => parent::STRING,
        'dt_cyunyu_r' => parent::STRING,
        'nm_biko' => parent::STRING,
        'dt_hattyuu_p' => parent::STRING,
        'dt_hattyuu_r' => parent::STRING,
        'dt_sikyuu_p' => parent::STRING,
        'dt_sikyuu_r' => parent::STRING,
        'dt_ukeire_p' => parent::STRING,
        'dt_ukeire_r' => parent::STRING,
        'dt_sikens_p' => parent::STRING,
        'dt_sikens_r' => parent::STRING,
        'dt_sikene_p' => parent::STRING,
        'dt_sikene_r' => parent::STRING,
        'dt_kyakutatis_p' => parent::STRING,
        'dt_kyakutatis_r' => parent::STRING,
        'dt_kyakutatie_p' => parent::STRING,
        'dt_kyakutatie_r' => parent::STRING,
        'dt_virus_p' => parent::STRING,
        'dt_virus_r' => parent::STRING,
        'dt_soutatu_p' => parent::STRING,
        'dt_soutatu_r' => parent::STRING,
        'no_siteki_s' => parent::INTEGER,
        'no_siteki_g' => parent::INTEGER,
        'dt_pmh_p' => parent::STRING,
        'dt_pmh_r' => parent::STRING,
        'dt_tov_p' => parent::STRING,
        'dt_tov_r' => parent::STRING,
        'nm_fg_fuyo' => parent::STRING,
        'no_page' => parent::INTEGER,
        'dt_tehai_p' => parent::STRING,
        'dt_tehai_r' => parent::STRING,
        'dt_drf1_p' => parent::STRING,
        'dt_drf1_r' => parent::STRING,
        'dt_service_p' => parent::STRING,
        'dt_service_r' => parent::STRING,

        'nm_page' => parent::STRING,
        'nm_caution' => parent::STRING,

        'dt_doc_yokyu_hw_p' => parent::STRING,
        'dt_doc_yokyu_sw_p' => parent::STRING,
        'dt_doc_irai_hw_p' => parent::STRING,
        'dt_doc_irai_sw_p' => parent::STRING,
        'dt_doc_henkyaku_hw_p' => parent::STRING,
        'dt_doc_henkyaku_sw_p' => parent::STRING,
        'dt_doc_seisaku_hw_p' => parent::STRING,
        'dt_doc_seisaku_sw_p' => parent::STRING,
        'dt_doc_siken_p' => parent::STRING,
        'dt_doc_kouji_p' => parent::STRING,
        'dt_doc_gentyo_p' => parent::STRING,
        'dt_doc_siji_p' => parent::STRING,
        'dt_doc_yoryo_p' => parent::STRING,
        'dt_doc_henkyaku_p' => parent::STRING,
        'dt_sikyuu2_p' => parent::STRING,
        'dt_seizous_hw_p' => parent::STRING,
        'dt_seizous_sw_p' => parent::STRING,
        'dt_seizoue_hw_p' => parent::STRING,
        'dt_seizoue_sw_p' => parent::STRING,
        'dt_syatatis_p' => parent::STRING,
        'dt_syatatie_p' => parent::STRING,
        'dt_hannyus_p' => parent::STRING,
        'dt_hannyug_p' => parent::STRING,
        'dt_suiage_p' => parent::STRING,
        'dt_tyousa_p' => parent::STRING,
        //計画のみ
        'dt_hatuban_p' => parent::STRING,

        'dt_doc_yokyu_hw_r' => parent::STRING,
        'dt_doc_yokyu_sw_r' => parent::STRING,
        'dt_doc_irai_hw_r' => parent::STRING,
        'dt_doc_irai_sw_r' => parent::STRING,
        'dt_doc_henkyaku_hw_r' => parent::STRING,
        'dt_doc_henkyaku_sw_r' => parent::STRING,
        'dt_doc_seisaku_hw_r' => parent::STRING,
        'dt_doc_seisaku_sw_r' => parent::STRING,
        'dt_doc_siken_r' => parent::STRING,
        'dt_doc_kouji_r' => parent::STRING,
        'dt_doc_gentyo_r' => parent::STRING,
        'dt_doc_siji_r' => parent::STRING,
        'dt_doc_yoryo_r' => parent::STRING,
        'dt_doc_henkyaku_r' => parent::STRING,
        'dt_sikyuu2_r' => parent::STRING,
        'dt_seizous_hw_r' => parent::STRING,
        'dt_seizous_sw_r' => parent::STRING,
        'dt_seizoue_hw_r' => parent::STRING,
        'dt_seizoue_sw_r' => parent::STRING,
        'dt_syatatis_r' => parent::STRING,
        'dt_syatatie_r' => parent::STRING,
        'dt_hannyus_r' => parent::STRING,
        'dt_hannyug_r' => parent::STRING,
        'dt_suiage_r' => parent::STRING,
        'dt_tyousa_r' => parent::STRING,
        'dt_wf_p' => parent::STRING,
        'dt_wf_r' => parent::STRING,

        //実績のみ
        'dt_zenkai_r' => parent::STRING,
        'dt_kouki_r' => parent::STRING,

    ];

    /**
    *   @var string[]
    */
    protected static array $real_only_properties = [
        'dt_kouki_r', 'dt_zenkai_r',
    ];

    /**
    *   @var string[]
    */
    protected static array $plan_only_properties = [
        'dt_hatuban_p',
    ];

    /**
    *   getRealOnlyProperties
    *
    *   @return string[]
    */
    public static function getRealOnlyProperties(): array
    {
        return self::$real_only_properties;
    }

    /**
    *   getPlanOnlyProperties
    *
    *   @return string[]
    */
    public static function getPlanOnlyProperties(): array
    {
        return self::$plan_only_properties;
    }

    public function isValid(): bool
    {
        $this->valid = [];
        $flg = true;
        if (!empty($this->data)) {
            foreach ((array)$this->data as $key => $val) {
                $function = 'isValid' . ucfirst($key);

                if (
                    method_exists(get_called_class(), $function) ||
                    mb_ereg_match('^isValidDt_.*$', $function)
                ) {
                    $ans = $this->$function($val);

                    if ($ans === true) {
                    } elseif ($ans === false) {
                        $this->valid[$key] = [''];
                        $flg = false;
                    } elseif (is_array($ans)) {
                        $this->valid[$key] = $ans;
                        $flg = false;
                    } else {
                        $this->valid[$key] = [$ans];
                        $flg = false;
                    }
                }
            }
        }
        return $flg;
    }

    /**
    *   @inheritDoc
    *
    *   @return bool
    */
    public function __call(
        string $name,
        array $arguments
    ): bool {
        if (!mb_ereg_match('^isValidDt_.*$', $name)) {
            throw new InvalidArgumentException(
                "__call error:no method called {$name}"
            );
        }
        return $this->isValidDate($arguments[0] ?? null);
    }

    //日付判定
    private function isValidDate(
        mixed $val
    ): bool {
        if (
            is_null($val) ||
            $val === '' ||
            $val === '00000000'
        ) {
            return true;
        }
        return Validate::isTextDate($val);
    }


    public function isValidKb_nendo(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isNendo($val);
    }

    public function isValidNo_cyu(
        mixed $val
    ): bool {
        return Validate::isCyuban($val);
    }

    public function isValidCd_bumon(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isBumon($val);
    }

    public function isValidNo_cyu_t(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isAscii($val, 0, 16);
    }

    public function isValidCd_tanto(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isTanto($val);
    }

    public function isValidNo_tm_wt(
        mixed $val
    ): bool {
        if (is_null($val)) {
            return true;
        }
        return Validate::isFloat($val);
    }

    //nm_cd_syukka

    public function isValidNm_zanken_s(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isText($val);
    }

    public function isValidNm_zanken_g(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isText($val);
    }

    public function isValidNo_zanken_s(
        mixed $val
    ): bool {
        if (is_null($val)) {
            return true;
        }
        return Validate::isInt($val);
    }

    public function isValidNo_zanken_g(
        mixed $val
    ): bool {
        if (is_null($val)) {
            return true;
        }
        return Validate::isInt($val);
    }

    public function isValidNm_biko(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isText($val);
    }

    public function isValidNo_siteki_s(
        mixed $val
    ): bool {
        if (is_null($val)) {
            return true;
        }
        return Validate::isInt($val);
    }

    public function isValidNo_siteki_g(
        mixed $val
    ): bool {
        if (is_null($val)) {
            return true;
        }
        return Validate::isInt($val);
    }

    public function isValidNm_fg_fuyo(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isTextBool($val);
    }

    public function isValidNo_page(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }

    public function isValidNm_page(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isText($val);
    }

    public function isValidNm_caution(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isTextBool($val);
    }

    /**
    *   出荷番号記号取得
    *
    *   @param string $date 取得基準日
    *   @return string 出荷番号記号
    */
    public function getSyukkaKey(
        string $date
    ): string {
        $yyyy = (int)mb_substr($date, 0, 4) - 2012;
        $mm = mb_substr($date, 4, 2);

        if (($yyyy < 0) || ($yyyy >= 26)) {
            throw new InvalidArgumentException(
                "out of range:{$date}"
            );
        }

        if (($mm >= '04') && ($mm <= '09')) {
            return chr($yyyy + 65) . 'K';
        }
        if (($mm >= '10') && ($mm <= '12')) {
            return chr($yyyy + 65) . 'S';
        }
        if (($mm >= '01') && ($mm <= '03')) {
            return chr($yyyy - 1 + 65) . 'S';
        } else {
            throw new InvalidArgumentException(
                "out of range:{$date}"
            );
        }
    }
}
