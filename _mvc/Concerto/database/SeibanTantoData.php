<?php

/**
*   seiban_tanto
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

/**
*   @extends ModelData<string>
*/
class SeibanTantoData extends ModelData
{
    /**
    *   @var string
    */
    public const MANUAL = 'M';

    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'ins_date' => parent::STRING,
        'no_cyu' => parent::STRING,
        'cd_tanto' => parent::STRING,
        'no_seq' => parent::STRING,
        'no_ko' => parent::STRING,
    ];

    public function isValidIns_date(
        mixed $val
    ): bool {
        return Validate::isTextDateTime($val);
    }

    public function isValidNo_cyu(
        mixed $val
    ): bool {
        return Validate::isCyuban($val);
    }

    public function isValidCd_tanto(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }

    public function isValidNo_Seq(
        mixed $val
    ): bool {
        if ($val === 'M') {
            return true;
        }
        return Validate::isTextInt($val, 0);
    }

    public function isValidNo_ko(
        mixed $val
    ): bool {
        if ($val === '') {
            return true;
        }
        return Validate::isKoban($val);
    }
}
