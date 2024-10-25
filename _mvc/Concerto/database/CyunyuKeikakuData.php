<?php

/**
*   cyunyu_keikaku
*
*   @version 230927
*/

declare(strict_types=1);

namespace Concerto\database;

use InvalidArgumentException;
use Concerto\standard\ModelData;
use Concerto\Validate;

/**
*   @extends ModelData<string|int|float>
*/
class CyunyuKeikakuData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'update' => parent::STRING,
        'editor' => parent::STRING,
        'no_id' => parent::INTEGER,
        'no_cyu' => parent::STRING,
        'no_ko' => parent::STRING,
        'dt_kanjyo' => parent::STRING,
        'cd_genka_yoso' => parent::STRING,
        'cd_bumon' => parent::STRING,
        'nm_cyunyu' => parent::STRING,
        'nm_syohin' => parent::STRING,
        'tm_cyokka' => parent::DOUBLE,
        'yn_money' => parent::INTEGER,
    ];

    /**
    *   非連想配列一括入力用対応テーブル
    *
    *   @var string[]
    */
    protected static array $number_table = [
        0 => 'update',
        1 => 'editor',
        2 => 'no_id',
        3 => 'no_cyu',
        4 => 'no_ko',
        5 => 'dt_kanjyo',
        6 => 'cd_genka_yoso',
        7 => 'cd_bumon',
        8 => 'nm_cyunyu',
        9 => 'nm_syohin',
        10 => 'tm_cyokka',
        11 => 'yn_money'
    ];

    /**
    *   数値配列一括入力
    *
    *   @param mixed[] $array
    *   @return void
    */
    public function fromNumberArray(
        array $array
    ): void {
        foreach ($array as $key => $val) {
            if (
                !array_key_exists($key, static::$number_table)
            ) {
                throw new InvalidArgumentException(
                    "array key not exists:{$key}"
                );
            }

            $prop = static::$number_table[$key];
            $type = static::$schema[$prop];

            switch ($type) {
                case parent::INTEGER:
                    $data = intval(
                        filter_var(
                            $val,
                            FILTER_SANITIZE_NUMBER_INT
                        )
                    );
                    break;
                case parent::DOUBLE:
                    $data = floatval(
                        filter_var(
                            $val,
                            FILTER_SANITIZE_NUMBER_FLOAT,
                            FILTER_FLAG_ALLOW_FRACTION,
                        )
                    );
                    break;
                default:
                    $data = $val;
            }
            $this->data[$prop] = $data;
        }
    }

    /**
    *   数値配列一括出力
    *
    *   @return mixed[]
    */
    public function toNumberArray(): array
    {
        $tables = static::$number_table;
        ksort($tables, SORT_NUMERIC);
        $items = [];

        foreach ($tables as $val) {
            $items[] = $this->data[$val];
        }
        return $items;
    }

    public function isValidUpdate(
        mixed $val
    ): bool {
        return Validate::isTextDateTime($val);
    }

    public function isValidEditor(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }

    public function isValidNo_id(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0, 1000);
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

    public function isValidDt_kanjyo(
        mixed $val
    ): bool {
        return Validate::isTextDateYYYYMM($val);
    }

    public function isValidCd_genka_yoso(
        mixed $val
    ): bool {
        return Validate::isGenkaYoso($val);
    }

    public function isValidCd_bumon(
        mixed $val
    ): bool {
        return Validate::isBumon($val);
    }

    public function isValidNm_cyunyu(
        mixed $val
    ): bool {
        if ($this->cd_genka_yoso === 'C1') {
            return Validate::isTanto($val);
        }
        return Validate::isTextEscape($val, 0, 100) &&
            !Validate::hasTextHankaku($val) &&
            !Validate::hasTextHtml($val) &&
            !Validate::hasTextDatabase($val);
    }

    public function isValidNm_syohin(
        mixed $val
    ): bool {
        if ($val === '') {
            return true;
        }
        return Validate::isTextEscape($val, 0, 100) &&
            !Validate::hasTextHankaku($val) &&
            !Validate::hasTextHtml($val) &&
            !Validate::hasTextDatabase($val);
    }

    public function isValidTm_cyokka(
        mixed $val
    ): bool {
        return Validate::isFloat($val);
    }

    public function isValidYn_money(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }
}
