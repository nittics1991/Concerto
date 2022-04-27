<?php

/**
*   koban_tyousei
*
*   @version 210903
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class KobanTyouseiData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'up_date' => parent::STRING,
        'editor' => parent::STRING,
        'no_cyu' => parent::STRING,
        'no_ko' => parent::STRING,
        'yn_ttov' => parent::STRING,
        'yn_tsoneki' => parent::STRING,
        'nm_biko' => parent::STRING,
        'cd_kansei' => parent::STRING,
    ];

    public function isValidUp_date($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidEditor($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidNo_cyu($val)
    {
        return Validate::isCyuban($val);
    }

    public function isValidNo_ko($val)
    {
        return Validate::isKoban($val);
    }

    public function isValidYn_ttov($val)
    {
        if ($val == '') {
            return true;
        }
        return Validate::isTextInt($val);
    }

    public function isValidYn_tsoneki($val)
    {
        if ($val == '') {
            return true;
        }
        return Validate::isTextInt($val);
    }

    public function isValidNm_biko($val)
    {
        return
            Validate::isTextEscape($val, 0, 100, null, '\r\n\t') &&
            !Validate::hasTextHankaku($val) &&
            !Validate::hasTextHtml($val) &&
            !Validate::hasTextDatabase($val);
    }

    public function isValidCd_kansei($val)
    {
        if ($val == '') {
            return true;
        }
        return Validate::isTextBool($val);
    }
}
