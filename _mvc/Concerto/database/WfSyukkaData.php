<?php

/**
*   wf_syukka
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
class WfSyukkaData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
         'no_cyu' => parent::STRING,
         'no_page' => parent::INTEGER,
         'cd_type' => parent::STRING,
         'dt_yotei' => parent::STRING,
         'cd_syubetu' => parent::STRING,
         'no_bunkatu_n' => parent::INTEGER,
         'no_bunkatu_d' => parent::INTEGER,
         'no_seisaku' => parent::INTEGER,
         'no_seisaku_n' => parent::INTEGER,
         'no_seisaku_d' => parent::INTEGER,
         'no_siken' => parent::INTEGER,
         'no_siken_n' => parent::INTEGER,
         'no_siken_d' => parent::INTEGER,
         'no_futeki_n' => parent::INTEGER,
         'no_futeki_d' => parent::INTEGER,
         'cd_ebidensu' => parent::STRING,
         'dt_kensa' => parent::STRING,
         'cd_kensa' => parent::STRING,
         'dt_hantei' => parent::STRING,
         'cd_hantei' => parent::STRING,
         'cd_hyouka1' => parent::STRING,
         'cd_hyouka2' => parent::STRING,
         'cd_hyouka3' => parent::STRING,
         'cd_hyouka4' => parent::STRING,
         'nm_comment' => parent::STRING,
         'nm_jyouken' => parent::STRING,
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

    public function isValidCd_type(
        mixed $val
    ): bool {
        return Validate::isText($val, 0);
    }

    public function isValidDt_yotei(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidCd_syubetu(
        mixed $val
    ): bool {
        return is_string($val) &&
            mb_ereg_match('^[0-9]*$', $val);
    }

    public function isValidNo_bunkatu_n(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidNo_bunkatu_d(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidNo_seisaku(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0, 100);
    }

    public function isValidNo_seisaku_n(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidNo_seisaku_d(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidNo_siken(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0, 100);
    }

    public function isValidNo_siken_n(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidNo_siken_d(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidNo_futeki_n(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidNo_futeki_d(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidCd_ebidensu(
        mixed $val
    ): bool {
        return is_string($val) &&
            mb_ereg_match('^[0-9]*$', $val);
    }

    public function isValidDt_kensa(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isTextDate($val);
    }

    public function isValidCd_kensa(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isTextInt($val, 0, 2);
    }

    public function isValidDt_hantei(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isTextDate($val);
    }

    public function isValidCd_hantei(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isTextInt($val, 0, 1);
    }

    public function isValidCd_hyouka1(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isTextInt($val, 0, 2);
    }

    public function isValidCd_hyouka2(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isTextInt($val, 0, 2);
    }

    public function isValidCd_hyouka3(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isTextInt($val, 0, 2);
    }

    public function isValidCd_hyouka4(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isTextInt($val, 0, 2);
    }

    public function isValidNm_comment(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isText($val);
    }

    public function isValidNm_jyoken(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isText($val);
    }
}
