<?php

/**
*   claim_sonhi
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\database\ClaimInfData;
use Concerto\standard\ModelData;
use Concerto\Validate;

class ClaimSonhiData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
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

    public function isValidNo_claim(
        mixed $val
    ): bool {
        return ClaimInfData::isValidNo_claim($val);
    }

    public function isValidDt_syukka(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidDt_psaihatu(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidDt_saihatu(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidCd_saihatu(
        mixed $val
    ): bool {
        return Validate::isTextInt($val, 0, 4);
    }

    public function isValidFg_lot(
        mixed $val
    ): bool {
        return Validate::isTextBool($val);
    }

    public function isValidFg_saihatu(
        mixed $val
    ): bool {
        return Validate::isTextBool($val);
    }

    public function isValidFg_sonhi(
        mixed $val
    ): bool {
        return Validate::isTextBool($val);
    }

    public function isValidYn_yusyo(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }

    public function isValidYn_syoryaku(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }

    public function isValidKb_hiyo(
        mixed $val
    ): bool {
        return Validate::isTextBool($val);
    }

    public function isValidTm_cyokka(
        mixed $val
    ): bool {
        return Validate::isFloat($val);
    }

    public function isValidYn_cyokka(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }

    public function isValidYn_keihi(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }
}
