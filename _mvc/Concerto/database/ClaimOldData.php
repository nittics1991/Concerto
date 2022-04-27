<?php

/**
*   claim_old
*
*   @version 200107
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\database\ClaimInfData;
use Concerto\standard\ModelData;
use Concerto\Validate;

class ClaimOldData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'no_claim' => parent::STRING,
        'no_seq' => parent::INTEGER,
        'nm_system' => parent::STRING,
        'nm_kaisyu' => parent::STRING,
        'kb_sts' => parent::STRING,
        'cd_sts' => parent::STRING,
    ];

    public function isValidNo_claim($val)
    {
        return ClaimInfData::isValidNo_claim($val);
    }

    public function isValidNo_seq($val)
    {
        return Validate::istextInt($val, 0);
    }

    public function isValidNm_system($val)
    {
        return Validate::isTextEscape($val);
    }

    public function isValidNm_kaisyu($val)
    {
        return Validate::isTextEscape($val);
    }

    public function isValidKb_sts($val)
    {
        return Validate::istextBool($val);
    }

    public function isValidCd_sts($val)
    {
        return Validate::isTextEscape($val);
    }
}
