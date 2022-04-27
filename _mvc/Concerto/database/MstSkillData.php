<?php

/**
*   mst_skill
*
*   @version 210903
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class MstSkillData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'cd_skill' => parent::STRING,
        'nm_skill' => parent::STRING,
        'dt_yukou' => parent::INTEGER,
        'cd_parent' => parent::STRING,
        'path' => parent::STRING,
        'depth' => parent::INTEGER,
    ];

    /**
    *   分類コードを分類毎に分解
    *
    *   @return array
    */
    public function splitBunrui()
    {
        $result[0] = mb_substr($this->cd_skill, 0, 1);
        $result[1] = mb_substr($this->cd_skill, 1, 2);
        $result[2] = mb_substr($this->cd_skill, 3, 3);
        $result[3] = mb_substr($this->cd_skill, 6, 2);
        $result[4] = mb_substr($this->cd_skill, 8, 2);

        return $result;
    }

    /**
    *   分類コードの所属(分類n?)
    *
    *   @return int 分類番号0-4
    */
    public function belongToBunrui()
    {
        $split = $this->splitBunrui();

        for ($i = 4; $i >= 0; $i--) {
            if (intval($split[$i]) > 0) {
                return $i;
            }
        }
        return $i;
    }

    /**
    *   分類コードインクリメント
    *
    *   @param bool $isParent true:親コードとして処理
    *   @return string
    */
    public function incrementBunrui($isParent = false)
    {
        $split = $this->splitBunrui();
        $position = $this->belongToBunrui();

        if ($isParent) {
            $position++;
        }

        $length = mb_strlen($split[$position]);

        $split[$position] = sprintf(
            "%0{$length}d",
            ++$split[$position]
        );

        return implode('', $split);
    }

    public function isValidCd_skill($val)
    {
        return Validate::isText($val, 10, 10) &&
            Validate::isTextInt($val, 1);
    }

    public function isValidNm_skill($val)
    {
        return Validate::isText($val, 1);
    }

    public function isValidDt_Yuyou($val)
    {
        return Validate::isTextInt($val, 0);
    }

    public function isValidCd_parent($val)
    {
        if ($val == '') {
            return true;
        }
        return Validate::isText($val, 10, 10) &&
            Validate::isTextInt($val, 1);
    }

    public function isValidPath($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isText($val, 1);
    }

    public function isValidDepth($val)
    {
        if (is_null($val)) {
            return true;
        }
        return Validate::isTextInt($val, 0);
    }
}
