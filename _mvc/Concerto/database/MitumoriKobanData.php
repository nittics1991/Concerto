<?php

/**
*   mitumori_koban
*
*   @version 211022
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class MitumoriKobanData extends ModelData
{
    /**
    *   項番数
    *
    *   @var int
    */
    public const NO_KOBAN_LINE = 30;

    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'up_date' => parent::STRING,
        'editor' => parent::STRING,
        'no_mitumori' => parent::STRING,
        'no_seq' => parent::INTEGER,
        'nm_syohin' => parent::STRING,
        'cd_bumon' => parent::STRING,
        'yn_tov' => parent::INTEGER,
        'tm_cyokka' => parent::INTEGER,
    ];

    public function isValidUp_date($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidEditor($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidNo_mitumori($val)
    {
        return Validate::isMitumoriNo($val);
    }

    public function isValidNo_seq($val)
    {
        return Validate::isInt($val, 0);
    }

    public function isValidNm_syohin($val)
    {
        return Validate::isText($val);
    }

    public function isValidCd_bumon($val)
    {
        return Validate::isBumon($val);
    }

    public function isValidYn_tov($val)
    {
        return Validate::isInt($val);
    }

    public function isValidTm_cyokka($val)
    {
        return Validate::isInt($val);
    }
}
