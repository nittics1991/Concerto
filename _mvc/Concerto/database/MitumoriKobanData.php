<?php

/**
*   mitumori_koban
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

/**
*   @extends ModelData<string|int>
*/
class MitumoriKobanData extends ModelData
{
    /**
    *   @var int
    */
    public const NO_KOBAN_LINE = 30;

    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'up_date' => parent::STRING,
        'editor' => parent::STRING,
        'no_mitumori' => parent::STRING,
        'no_seq' => parent::INTEGER,
        'nm_syohin' => parent::STRING,
        'cd_bumon' => parent::STRING,
        'yn_tov' => parent::INTEGER,
        'tm_cyokka' => parent::INTEGER,
    ];

    public function isValidUp_date(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidEditor(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }

    public function isValidNo_mitumori(
        mixed $val
    ): bool {
        return Validate::isMitumoriNo($val);
    }

    public function isValidNo_seq(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidNm_syohin(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidCd_bumon(
        mixed $val
    ): bool {
        return Validate::isBumon($val);
    }

    public function isValidYn_tov(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }

    public function isValidTm_cyokka(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }
}
