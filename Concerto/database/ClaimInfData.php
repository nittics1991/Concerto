<?php

/**
*   claim_inf
*
*   @version 200107
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class ClaimInfData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
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
    
    public function isValidNo_claim($val)
    {
        return Validate::isCyuban($val);
    }
    
    public function isValidKb_nendo($val)
    {
        return Validate::isNendo($val);
    }
    
    public function isValidCd_bumon($val)
    {
        return Validate::isBumon($val);
    }
    
    public function isValidNo_cyu($val)
    {
        return Validate::isCyuban($val);
    }
    
    public function isValidNo_cyu_zn($val)
    {
        return Validate::isCyuban($val);
    }
    
    public function isValidNm_user($val)
    {
        return Validate::isTextEscape($val);
    }
    
    public function isValidNm_system($val)
    {
        return Validate::isTextEscape($val);
    }
    
    public function isValidCd_tanto($val)
    {
        return Validate::isTanto($val);
    }
    
    public function isValidNm_renraku($val)
    {
        return Validate::isTextEscape($val);
    }
    
    public function isValidDt_hassei($val)
    {
        return Validate::isTextDate($val);
    }
    
    public function isValidNm_mondai($val)
    {
        return Validate::isTextEscape($val);
    }
    
    public function isValidNm_keika($val)
    {
        return Validate::isTextEscape($val);
    }
    
    public function isValidNm_genin($val)
    {
        return Validate::isTextEscape($val);
    }
    
    public function isValidNm_taisaku($val)
    {
        return Validate::isTextEscape($val);
    }
    
    public function isValidNm_saihatu($val)
    {
        return Validate::isTextEscape($val);
    }
    
    public function isValidDt_kaisyu($val)
    {
        return Validate::isTextDate($val);
    }
    
    public function isValidNm_doc($val)
    {
        return Validate::isTextEscape($val);
    }
    
    public function isValidNm_pro($val)
    {
        return Validate::isTextEscape($val);
    }
    
    public function isValidDt_pkanryu($val)
    {
        return Validate::isTextDate($val);
    }
    
    public function isValidDt_kakunin($val)
    {
        return Validate::isTextDate($val);
    }
    
    public function isValidNm_kakunin($val)
    {
        return Validate::isTextEscape($val);
    }
    
    public function isValidNm_biko($val)
    {
        return Validate::isTextEscape($val);
    }
    
    public function isValidNm_tyousa($val)
    {
        return Validate::isTextEscape($val);
    }
    
    public function isValidNm_syonin($val)
    {
        return Validate::isTextEscape($val);
    }
    
    public function isValidIns_date($val)
    {
        return Validate::isTextDateTime($val);
    }
    
    public function isValidUpdate($val)
    {
        return Validate::isTextDateTime($val);
    }
    
    public function isValidEditor($val)
    {
        return Validate::isTanto($val);
    }
}
