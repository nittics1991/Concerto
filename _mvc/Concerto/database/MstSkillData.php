<?php

/**
*   mst_skill
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
class MstSkillData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
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
    *   @return string[]
    */
    public function splitBunrui(): array
    {
        $result[0] = mb_substr($this->cd_skill, 0, 1);
        $result[1] = mb_substr($this->cd_skill, 1, 2);
        $result[2] = mb_substr($this->cd_skill, 3, 3);
        $result[3] = mb_substr($this->cd_skill, 6, 2);
        $result[4] = mb_substr($this->cd_skill, 8, 2);

        return $result;
    }

    /**
    *   分類コードの所属(分類n 0-4)
    *
    *   @return int
    */
    public function belongToBunrui(): int
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
    public function incrementBunrui(
        bool $isParent = false
    ): string {
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

    public function isValidCd_skill(
        mixed $val
    ): bool {
        return Validate::isText($val, 10, 10) &&
            Validate::isTextInt($val, 1);
    }

    public function isValidNm_skill(
        mixed $val
    ): bool {
        return Validate::isText($val, 1);
    }

    public function isValidDt_Yuyou(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidCd_parent(
        mixed $val
    ): bool {
        if ($val === '') {
            return true;
        }
        return Validate::isText($val, 10, 10) &&
            Validate::isTextInt($val, 1);
    }

    public function isValidPath(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isText($val, 1);
    }

    public function isValidDepth(
        mixed $val
    ): bool {
        if (is_null($val)) {
            return true;
        }
        return Validate::isInt($val, 0);
    }
}
