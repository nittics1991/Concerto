<?php

/**
*   wf_new
*
*   @version 181030
*/

declare(strict_types=1);

namespace Concerto\database;

use InvalidArgumentException;
use Concerto\standard\ModelData;
use Concerto\Validate;

class WfNewData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = array(
        "kb_nendo" => parent::STRING
        , "no_cyu" => parent::STRING
        , "cd_bumon" => parent::STRING
        , "dt_dra_p" => parent::STRING
        , "dt_dra_r" => parent::STRING
        , "dt_drb_p" => parent::STRING
        , "dt_drb_r" => parent::STRING
        , "dt_drc_p" => parent::STRING
        , "dt_drc_r" => parent::STRING
        , "dt_drd_p" => parent::STRING
        , "dt_drd_r" => parent::STRING
        , "dt_dre_p" => parent::STRING
        , "dt_dre_r" => parent::STRING
        , "dt_drf_p" => parent::STRING
        , "dt_drf_r" => parent::STRING
        , "dt_drg_p" => parent::STRING
        , "dt_drg_r" => parent::STRING
        , "dt_drh_p" => parent::STRING
        , "dt_drh_r" => parent::STRING
        , "dt_drq_p" => parent::STRING
        , "dt_drq_r" => parent::STRING
        , "dt_drsi_p" => parent::STRING
        , "dt_drsi_r" => parent::STRING
        , "dt_drst_p" => parent::STRING
        , "dt_drst_r" => parent::STRING
        , "dt_syukka_p" => parent::STRING
        , "dt_syukka_r" => parent::STRING
        , "dt_gentis_p" => parent::STRING
        , "dt_gentis_r" => parent::STRING
        , "dt_gentie_p" => parent::STRING
        , "dt_gentie_r" => parent::STRING
        , "dt_cpi_p" => parent::STRING
        , "dt_cpi_r" => parent::STRING
        , "dt_cpt_p" => parent::STRING
        , "dt_cpt_r" => parent::STRING
        , "dt_doc_p" => parent::STRING
        , "dt_doc_r" => parent::STRING
        , "no_cyu_t" => parent::STRING
        , "cd_tanto" => parent::STRING
        , "tm_wt" => parent::FLOAT
        , "kb_kensa" => parent::STRING
        , "dt_zanken_s_p" => parent::STRING
        , "dt_zanken_s_r" => parent::STRING
        , "nm_zanken_s" => parent::STRING
        , "dt_zanken_g_p" => parent::STRING
        , "dt_zanken_g_r" => parent::STRING
        , "nm_zanken_g" => parent::STRING
        , "kb_zanken_s" => parent::INTEGER
        , "kb_zanken_g" => parent::INTEGER
        , "no_seq" => parent::STRING
        , "dt_koutei_p" => parent::STRING
        , "dt_koutei_r" => parent::STRING
        , "dt_cyotatu_p" => parent::STRING
        , "dt_cyotatu_r" => parent::STRING
        , "dt_cyunyu_p" => parent::STRING
        , "dt_cyunyu_r" => parent::STRING
        , "nm_biko" => parent::STRING
        , "dt_hattyuu_p" => parent::STRING
        , "dt_hattyuu_r" => parent::STRING
        , "dt_sikyuu_p" => parent::STRING
        , "dt_sikyuu_r" => parent::STRING
        , "dt_ukeire_p" => parent::STRING
        , "dt_ukeire_r" => parent::STRING
        , "dt_sikens_p" => parent::STRING
        , "dt_sikens_r" => parent::STRING
        , "dt_sikene_p" => parent::STRING
        , "dt_sikene_r" => parent::STRING
        , "dt_kyakutatis_p" => parent::STRING
        , "dt_kyakutatis_r" => parent::STRING
        , "dt_kyakutatie_p" => parent::STRING
        , "dt_kyakutatie_r" => parent::STRING
        , "dt_virus_p" => parent::STRING
        , "dt_virus_r" => parent::STRING
        , "dt_soutatu_p" => parent::STRING
        , "dt_soutatu_r" => parent::STRING
        , "kb_siteki_s" => parent::INTEGER
        , "kb_siteki_g" => parent::INTEGER
        , "dt_pmh_p" => parent::STRING
        , "dt_pmh_r" => parent::STRING
        , "dt_tov_p" => parent::STRING
        , "dt_tov_r" => parent::STRING
        , "fg_fuyo" => parent::STRING
        , "no_page" => parent::INTEGER
        , "dt_tehai_p" => parent::STRING
        , "dt_tehai_r" => parent::STRING
        , "kb_tehai" => parent::INTEGER
        , "dt_drf1_p" => parent::STRING
        , "dt_drf1_r" => parent::STRING
        , "dt_service_p" => parent::STRING
        , "dt_service_r" => parent::STRING
        
        , "nm_page" => parent::STRING
        , "no_rev" => parent::INTEGER
        
        , "dt_doc_yokyu_hw_p" => parent::STRING
        , "dt_doc_yokyu_sw_p" => parent::STRING
        , "dt_doc_irai_hw_p" => parent::STRING
        , "dt_doc_irai_sw_p" => parent::STRING
        , "dt_doc_henkyaku_hw_p" => parent::STRING
        , "dt_doc_henkyaku_sw_p" => parent::STRING
        , "dt_doc_seisaku_hw_p" => parent::STRING
        , "dt_doc_seisaku_sw_p" => parent::STRING
        , "dt_doc_siken_p" => parent::STRING
        , "dt_doc_kouji_p" => parent::STRING
        , "dt_doc_gentyo_p" => parent::STRING
        , "dt_doc_siji_p" => parent::STRING
        , "dt_doc_yoryo_p" => parent::STRING
        , "dt_doc_henkyaku_p" => parent::STRING
        , "dt_sikyuu2_p" => parent::STRING
        , "dt_seizous_hw_p" => parent::STRING
        , "dt_seizous_sw_p" => parent::STRING
        , "dt_seizoue_hw_p" => parent::STRING
        , "dt_seizoue_sw_p" => parent::STRING
        , "dt_syatatis_p" => parent::STRING
        , "dt_syatatie_p" => parent::STRING
        , "dt_hannyus_p" => parent::STRING
        , "dt_hannyug_p" => parent::STRING
        , "dt_suiage_p" => parent::STRING
        , "dt_tyousa_p" => parent::STRING
        , "dt_hatuban" => parent::STRING
        
        , "dt_doc_yokyu_hw_r" => parent::STRING
        , "dt_doc_yokyu_sw_r" => parent::STRING
        , "dt_doc_irai_hw_r" => parent::STRING
        , "dt_doc_irai_sw_r" => parent::STRING
        , "dt_doc_henkyaku_hw_r" => parent::STRING
        , "dt_doc_henkyaku_sw_r" => parent::STRING
        , "dt_doc_seisaku_hw_r" => parent::STRING
        , "dt_doc_seisaku_sw_r" => parent::STRING
        , "dt_doc_siken_r" => parent::STRING
        , "dt_doc_kouji_r" => parent::STRING
        , "dt_doc_gentyo_r" => parent::STRING
        , "dt_doc_siji_r" => parent::STRING
        , "dt_doc_yoryo_r" => parent::STRING
        , "dt_doc_henkyaku_r" => parent::STRING
        , "dt_sikyuu2_r" => parent::STRING
        , "dt_seizous_hw_r" => parent::STRING
        , "dt_seizous_sw_r" => parent::STRING
        , "dt_seizoue_hw_r" => parent::STRING
        , "dt_seizoue_sw_r" => parent::STRING
        , "dt_syatatis_r" => parent::STRING
        , "dt_syatatie_r" => parent::STRING
        , "dt_hannyus_r" => parent::STRING
        , "dt_hannyug_r" => parent::STRING
        , "dt_suiage_r" => parent::STRING
        , "dt_tyousa_r" => parent::STRING
        
        , "dt_zenkai" => parent::STRING
        , "dt_kouki" => parent::STRING
        
        , "dt_wf_p" => parent::STRING
        , "dt_wf_r" => parent::STRING
        
        , "update" => parent::STRING
        , "editor" => parent::STRING
    );
    
    
    /**
    *   バリデート(overwite)
    *
    *   @return bool 結果
    */
    public function isValid()
    {
        $this->valid = array();
        $flg = true;
        if (!empty($this->data)) {
            foreach ((array)$this->data as $key => $val) {
                $function = 'isValid' . ucfirst($key);
                
                //==>change
                //if (method_exists(get_called_class(), $function)) {
                if (
                    (method_exists(get_called_class(), $function)
                    || (mb_ereg_match('^isValidDt_.*$', $function)))
                ) {
                //<==change
                    $ans = $this->$function($val);
                    
                    if ($ans === true) {
                    } elseif ($ans == false) {
                        $this->valid[$key] = array('');
                        $flg = false;
                    } elseif (is_array($ans)) {
                        $this->valid[$key] = $ans;
                        $flg = false;
                    } else {
                        $this->valid[$key] = array($ans);
                        $flg = false;
                    }
                }
            }
        }
        return $flg;
    }
    
    /**
    *   マジックメソッド
    *
    *   @param string $method method名
    *   @param array $argv 引数
    *   @return mixed
    *   @throws InvalidArgumentException
    **/
    public function __call($method, $argv)
    {
        if (!mb_ereg_match('^isValidDt_.*$', $method)) {
            throw new InvalidArgumentException("__call error:no method called {$method}");
        }
        return $this->isValidDate($argv[0]);
    }
    
    //日付判定
    private function isValidDate($val)
    {
        if (is_null($val) || ($val == '') || ($val == '00000000')) {
            return true;
        }
        return Validate::isTextDate($val);
    }
    
    
    public function isValidKb_nendo($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isNendo($val);
    }
    
    public function isValidNo_cyu($val)
    {
        return Validate::isCyuban($val);
    }
    
    public function isValidCd_bumon($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isBumon($val);
    }
    
    public function isValidNo_cyu_t($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isAscii($val, 0, 16);
    }
    
    public function isValidCd_tanto($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isTanto($val);
    }
    
    public function isValidTm_wt($val)
    {
        if (is_null($val)) {
            return true;
        }
        return Validate::isFloat($val);
    }
    
    //kb_kensa
    
    public function isValidNm_zanken_s($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isText($val);
    }
    
    public function isValidNm_zanken_g($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isText($val);
    }
    
    public function isValidKb_zanken_s($val)
    {
        if (is_null($val)) {
            return true;
        }
        return Validate::isTextBool($val);
    }
    
    public function isValidKb_zanken_g($val)
    {
        if (is_null($val)) {
            return true;
        }
        return Validate::isTextBool($val);
    }
    
    public function isValidNo_seq($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        } else {
            if (mb_check_encoding($val) && is_string($val) && preg_match('/[0-9]{3}/', $val)) {
                return true;
            }
            return false;
        }
    }
    
    public function isValidNm_biko($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isText($val);
    }
    
    public function isValidKb_siteki_s($val)
    {
        if (is_null($val)) {
            return true;
        }
        return Validate::isInt($val);
    }
    
    public function isValidKb_siteki_g($val)
    {
        if (is_null($val)) {
            return true;
        }
        return Validate::isInt($val);
    }
    
    public function isValidFg_fuyo($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isTextBool($val);
    }
    
    public function isValidNo_page($val)
    {
        return Validate::isInt($val);
    }
    
    public function isValidKb_tehai($val)
    {
        if (is_null($val)) {
            return true;
        }
        return Validate::isInt($val, 0, 1);
    }
    
    public function isValidNm_page($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isText($val);
    }
    
    public function isValidNo_Rev($val)
    {
        if (is_null($val)) {
            return true;
        }
        return Validate::isInt($val, 0);
    }
    
    /**
    *   出荷番号記号取得
    *
    *   @param string $date 取得基準日
    *   @return string 出荷番号記号
    *   @throws InvalidArgumentException
    */
    public function getSyukkaKey($date)
    {
        $yyyy = (int)mb_substr($date, 0, 4) - 2012;
        $mm = mb_substr($date, 4, 2);
        
        if (($yyyy < 0) || ($yyyy >= 26)) {
            throw new InvalidArgumentException("out of range:{$date}");
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
            throw new InvalidArgumentException("out of range:{$date}");
        }
    }
}
