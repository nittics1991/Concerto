<?php

/**
*   claim_sonhi
*
*   @version 200107
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\database\ClaimInfData;
use Concerto\standard\ModelData;
use Concerto\Validate;

class ClaimSonhiData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'no_claim' => parent::STRING,
        'dt_syukka' => parent::STRING,
        'dt_psaihatu' => parent::STRING,
        'dt_saihatu' => parent::STRING,
        'cd_saihatu' => parent::STRING,
        'fg_lot' => parent::STRING,
        'fg_saihatu' => parent::STRING,
        'fg_sonhi' => parent::STRING,
        'yn_yusyo' => parent::INTEGER,
        'yn_syoryaku' => parent::INTEGER,
        'kb_hiyou' => parent::STRING,
        'tm_cyokka' => parent::FLOAT,
        'yn_cyokka' => parent::INTEGER,
        'yn_keihi' => parent::INTEGER,
    ];

    public function isValidNo_claim($val)
    {
        return ClaimInfData::isValidNo_claim($val);
    }

    public function isValidDt_syukka($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidDt_psaihatu($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidDt_saihatu($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidCd_saihatu($val)
    {
        return Validate::isTextInt($val, 0, 4);
    }

    public function isValidFg_lot($val)
    {
        return Validate::isTextBool($val);
    }

    public function isValidFg_saihatu($val)
    {
        return Validate::isTextBool($val);
    }

    public function isValidFg_sonhi($val)
    {
        return Validate::isTextBool($val);
    }

    public function isValidYn_yusyo($val)
    {
        return Validate::isInt($val);
    }

    public function isValidYn_syoryaku($val)
    {
        return Validate::isInt($val);
    }

    public function isValidKb_hiyo($val)
    {
        return Validate::isTextBool($val);
    }

    public function isValidTm_cyokka($val)
    {
        return Validate::isFloat($val);
    }

    public function isValidYn_cyokka($val)
    {
        return Validate::isInt($val);
    }

    public function isValidYn_keihi($val)
    {
        return Validate::isInt($val);
    }
}
