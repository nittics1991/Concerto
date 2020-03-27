<?php

/**
*   cyuban_inf
*
*   @version 200326
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\database\CyunyuInfData;
use Concerto\database\MitumoriInfData;
use Concerto\database\MstBumonData;
use Concerto\database\MstTantoData;
use Concerto\standard\ModelData;
use Concerto\Validate;

class CyubanInfData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        "kb_nendo" => parent::STRING
        , "no_cyu" => parent::STRING
        , "cd_bumon" => parent::STRING
        , "dt_puriage" => parent::STRING
        , "kb_ukeoi" => parent::STRING
        , "kb_cyumon" => parent::STRING
        , "nm_syohin" => parent::STRING
        , "nm_setti" => parent::STRING
        , "nm_user" => parent::STRING
        , "dt_uriage" => parent::STRING
        , "kb_keikaku" => parent::STRING
        , "no_seq" => parent::INTEGER
        , "dt_hatuban" => parent::STRING
        , "nm_tanto" => parent::STRING
        , "dt_hakkou" => parent::STRING
        , "yn_sp" => parent::INTEGER
        , "yn_net" => parent::INTEGER
        , "cd_kisyu" => parent::STRING
        , "kb_kubun" => parent::STRING
        , "cd_tanto" => parent::STRING
        , "no_mitumori" => parent::STRING
    ];
    
    /**
    *   注文確度
    *
    *   @var array
    */
    private $kb_cyumon_list = ['受', 'Ａ', 'Ｂ', 'Ｃ', '仮'];
    
    /**
    */
    public function isValidKb_nendo($val)
    {
        return Validate::isNendo($val);
    }
    
    public function isValidNo_cyu($val)
    {
        return is_string($val)
            && mb_check_encoding($val)
            && mb_ereg_match('\A([A-Z,0-9]{7,8})\z', $val);
    }
    
    public function isValidCd_bumon($val)
    {
        return MstBumonData::isValidCd_Bumon($val);
    }
    
    public function isValidDt_puriage($val)
    {
        return Validate::isTextDateYYYYMM($val);
    }
    
    public function isValidKb_ukeoi($val)
    {
        return Validate::isTextInt($val, 0, 2);
    }
    
    public function isValidKb_cyumon($val)
    {
        return Validate::isTextInt($val, 0, 4);
    }
    
    public function isValidNm_syohin($val)
    {
        return Validate::isText($val);
    }
    
    public function isValidNm_setti($val)
    {
        return Validate::isText($val);
    }
    
    public function isValidNm_user($val)
    {
        return Validate::isText($val);
    }
    
    public function isValidDt_uriage($val)
    {
        return Validate::isTextDate($val);
    }
    
    public function isValidKb_keikaku($val)
    {
        return Validate::isTextBool($val);
    }
    
    public function isValidNo_seq($val)
    {
        return Validate::isInt($val, 0);
    }
    
    public function isValidDt_hatuban($val)
    {
        return Validate::isTextDate($val);
    }
    
    public function isValidNm_tanto($val)
    {
        return Validate::isText($val);
    }
    
    public function isValidDt_hakkou($val)
    {
        return Validate::isTextDate($val);
    }
    
    public function isValidyn_sp($val)
    {
        return Validate::isInt($val);
    }
    
    public function isValidyn_net($val)
    {
        return Validate::isInt($val);
    }
    
    public function isValidCd_kisyu($val)
    {
        return Validate::isAscii($val, 2, 2);
    }
    
    public function isValidCd_kubun($val)
    {
        return Validate::isAscii($val, 2, 2);
    }
    
    public function isValidCd_tanto($val)
    {
        return MstTantoData::isValidCd_tanto($val);
    }
    
    public function isValidNo_mitumori($val)
    {
        return MitumoriInfData::isValidNo_mitumori($val);
    }
    
    /**
    *   注文確度取得
    *
    *   @param string $id 注文確度
    *   @return string|array|null
    */
    public function getKbCyumon($id = null)
    {
        if (is_null($id)) {
            return $this->kb_cyumon_list;
        }
        
        if (array_key_exists($id, $this->kb_cyumon_list)) {
            return $this->kb_cyumon_list[$id];
        }
        return null;
    }
}
