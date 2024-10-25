<?php

/**
*   koban_tyousei
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
class KobanTyouseiData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'up_date' => parent::STRING,
        'editor' => parent::STRING,
        'no_cyu' => parent::STRING,
        'no_ko' => parent::STRING,
        'yn_ttov' => parent::STRING,
        'yn_tsoneki' => parent::STRING,
        'nm_biko' => parent::STRING,
        'cd_kansei' => parent::STRING,
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

    public function isValidNo_cyu(
        mixed $val
    ): bool {
        return Validate::isCyuban($val);
    }

    public function isValidNo_ko(
        mixed $val
    ): bool {
        return Validate::isKoban($val);
    }

    public function isValidYn_ttov(
        mixed $val
    ): bool {
        if ($val === '') {
            return true;
        }
        return Validate::isTextInt($val);
    }

    public function isValidYn_tsoneki(
        mixed $val
    ): bool {
        if ($val === '') {
            return true;
        }
        return Validate::isTextInt($val);
    }

    public function isValidNm_biko(
        mixed $val
    ): bool {
        return
            Validate::isTextEscape(
                $val,
                0,
                100,
                null,
                '\r\n\t'
            ) &&
            !Validate::hasTextHankaku($val) &&
            !Validate::hasTextHtml($val) &&
            !Validate::hasTextDatabase($val);
    }

    public function isValidCd_kansei(
        mixed $val
    ): bool {
        if ($val === '') {
            return true;
        }
        return Validate::isTextBool($val);
    }
}
