<?php

/**
*   tyotatu_komoku
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
class TyotatuKomokuData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
         'no_cyu' => parent::STRING,
         'no_page' => parent::INTEGER,
         'no_sheet' => parent::INTEGER,
         'no_line' => parent::INTEGER,
         'no_rev' => parent::INTEGER,
         'fg_delete' => parent::STRING,
         'nm_maker' => parent::STRING,
         'cd_syohin' => parent::STRING,
         'nm_syohin' => parent::STRING,
         'nm_model' => parent::STRING,
         'cd_torihiki' => parent::STRING,
         'no_tehai' => parent::STRING,
         'cd_sbl' => parent::STRING,
         'cd_part' => parent::STRING,
         'cd_tanto' => parent::STRING,
         'cd_user' => parent::STRING,
         'no_ko' => parent::STRING,
         'dt_ptehai' => parent::STRING,
         'dt_yokyu' => parent::STRING,
         'cd_unit' => parent::STRING,
         'no_suryo' => parent::INTEGER,
         'yn_tanka' => parent::INTEGER,
         'yn_hatuban' => parent::INTEGER,
         'yn_kijyun' => parent::INTEGER,
         'yn_target' => parent::INTEGER,
         'no_adr' => parent::STRING,
         'nm_adr' => parent::STRING,
         'no_tel' => parent::STRING,
         'nm_to' => parent::STRING,
         'fg_tehai' => parent::STRING,
         'no_cyumon' => parent::STRING,
         'nm_biko' => parent::STRING,
         'nm_biko2' => parent::STRING,
         'nm_tehai' => parent::STRING,
         'cd_routine' => parent::STRING,
    ];

    public function isValidNo_cyu(
        mixed $val
    ): bool {
        return Validate::isCyuban($val);
    }

    public function isValidNo_page(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidNo_sheet(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidNo_line(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidNo_rev(
        mixed $val
    ): bool {
        if (!isset($val)) {
            return true;
        }
        return Validate::isInt($val, 0);
    }

    public function isValidFg_delete(
        mixed $val
    ): bool {
        if (!isset($val) || $val === '') {
            return true;
        }
        return Validate::isTextBool($val);
    }

    //nm_maker
    //cd_syohin
    //nm_syohin
    //nm_model
    //cd_torihiki
    //no_tehai
    //cd_sbl
    //cd_part

    public function isValidCd_tanto(
        mixed $val
    ): bool {
        if (!isset($val) || $val === '') {
            return true;
        }
        return Validate::isTanto($val);
    }

    public function isValidCd_user(
        mixed $val
    ): bool {
        if (!isset($val) || $val === '') {
            return true;
        }
        return Validate::isUser($val);
    }

    public function isValidNo_ko(
        mixed $val
    ): bool {
        if (!isset($val) || $val === '') {
            return true;
        }
        return Validate::isKoban($val);
    }

    public function isValidDt_ptehai(
        mixed $val
    ): bool {
        if (!isset($val) || $val === '') {
            return true;
        }
        return Validate::isTextDate($val);
    }

    public function isValidDt_yokyu(
        mixed $val
    ): bool {
        if (!isset($val) || $val === '') {
            return true;
        }
        return Validate::isTextDate($val);
    }

    //cd_unit

    public function isValidNo_suryo(
        mixed $val
    ): bool {
        if (!isset($val)) {
            return true;
        }
        return Validate::isInt($val);
    }

    public function isValidYn_tanka(
        mixed $val
    ): bool {
        if (!isset($val)) {
            return true;
        }
        return Validate::isInt($val);
    }


    public function isValidYn_hatuban(
        mixed $val
    ): bool {
        if (!isset($val)) {
            return true;
        }
        return Validate::isInt($val);
    }

    public function isValidYn_kijyun(
        mixed $val
    ): bool {
        if (!isset($val)) {
            return true;
        }
        return Validate::isInt($val);
    }

    public function isValidYn_target(
        mixed $val
    ): bool {
        if (!isset($val)) {
            return true;
        }
        return Validate::isInt($val);
    }

    //no_adr
    //nm_adr
    //no_tel
    //nm_to

    public function isValidFg_tehai(
        mixed $val
    ): bool {
        if (!isset($val) || $val === '') {
            return true;
        }
        return Validate::isTextBool($val);
    }

    //no_cyumon
    //nm_biko
    //nm_biko2
    //nm_tehai
    //cd_routine
}
