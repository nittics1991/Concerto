<?php

/**
*   claim_doc
*
*   @version 200107
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\database\ClaimInfData;
use Concerto\standard\ModelData;
use Concerto\Validate;

class ClaimDocData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'no_claim' => parent::STRING
        , 'no_seq' => parent::INTEGER
        , 'nm_file' => parent::STRING
        , 'nm_file_inf' => parent::STRING
    ];

    public function isValidNo_claim($val)
    {
        return ClaimInfData::isValidNo_claim($val);
    }

    public function isValidNo_seq($val)
    {
        return Validate::isTextInt($val, 0);
    }

    public function isValidNm_file($val)
    {
        return Validate::isText($val, 0);
    }

    public function isValidNm_file_inf($val)
    {
        return Validate::isText($val, 0);
    }
}
