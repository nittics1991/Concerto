<?php

/**
*   mst_skill_tanto
*
*   @version 160831
**/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class MstSkillTantoData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = array(
        "update" => parent::STRING
        , "editor" => parent::STRING
        , "cd_tanto" => parent::STRING
        , "nm_tanto" => parent::STRING
        , "cd_status" => parent::STRING
        , "disp_seq" => parent::STRING
        , "cd_bumon" => parent::STRING
    );
    
    public function isValidUpdate($val)
    {
        return Validate::isTextDate($val);
    }
    
    public function isValidEditor($val)
    {
        return Validate::isTanto($val);
    }
    
    public function isValidCd_tanto($val)
    {
        return Validate::isTanto($val) && mb_ereg_match('\80\d{3}\z', $val);
    }
    
    public function isValidNm_tanto($val)
    {
        return Validate::isTextEscape($val, 0, null, null, '\r\n\t　')
            && !Validate::hasTextHankaku($val)
            && !Validate::hasTextHtml($val)
            && !Validate::hasTextDatabase($val);
    }
    
    public function isValidCd_status($val)
    {
        if ($val == '') {
            return true;
        }
        return Validate::isTextBool($val);
    }
    
    public function isValidDisp_seq($val)
    {
        if (mb_check_encoding($val) && mb_ereg_match('\A[\x20-\x7eぁ-ん]+\z', $val) && is_string($val)) {
            return true;
        }
        return false;
    }
    
    public function isValidCd_bumn($val)
    {
        return Validate::isBumon($val) && mb_ereg_match('\AX(S|E|T)[A-Z}\d{2}\z', $val);
    }
}
