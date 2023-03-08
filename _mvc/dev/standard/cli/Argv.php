<?php

/**
*   Argv
*
*   @version 210825
*   @caution getoptの動作は難しいので出来るだけ単純に
*       getoptで必須を指定して引数が無くてもエラーにはならない(validateで判定処理を入れる)
*       値あり引数の場合、入力時に=でつなぐ事を推奨
*/

declare(strict_types=1);

namespace Concerto\standard;

use RuntimeException;
use Concerto\standard\DataContainerValidatable;

class Argv extends DataContainerValidatable
{
    /**
    *   ショートオプション定義
    *
    *   @var string[]
    *   @see getopt
    */
    protected static $definedShortOptions = [];

    /**
    *   ロングオプション定義
    *
    *   @var string[]
    *   @see getopt
    */
    protected static $definedLongOptions = [];

    /**
    *   引数オプション定義
    *
    *   @var string[]
    *   @see getopt
    */
    protected static $definedOptindOptions = [];

    /**
    *   optind
    *
    *   @var int
    *   @see getopt
    */
    protected $optind;

    /**
    *   __construct
    *
    */
    public function __construct()
    {
        if (PHP_SAPI != 'cli') {
            throw new RuntimeException("must be run CLI mode");
        }
        $this->init();
    }

    /**
    *   初期化
    *
    */
    protected function init(): void
    {
        if (
            count(static::$definedShortOptions) == 0 &&
            count(static::$definedLongOptions) == 0 &&
            count(static::$definedOptindOptions) == 0
        ) {
            return;
        }

        $parsed = $this->defineProperty()
            ->getOpt();
        $this->setDataFromArgv($parsed)
            ->setOptindDataFromArgv();
    }

    /**
    *   property定義
    *
    *   @return $this
    */
    protected function defineProperty()
    {
        array_map(
            function ($defined) {
                $options = array_map(
                    function ($property) {
                        return mb_ereg_replace(':', '', $property);
                    },
                    static::$$defined
                );
                static::$schema = array_merge(
                    (array)static::$schema,
                    $options
                );
            },
            [
                'definedShortOptions',
                'definedLongOptions',
                'definedOptindOptions'
            ]
        );
        return $this;
    }

    /**
    *   getopt
    *
    *   @return string[]
    */
    protected function getOpt()
    {
        $parsed = getopt(
            implode('', static::$definedShortOptions),
            static::$definedLongOptions,
            $this->optind
        );

        if ($parsed === false) {
            throw new RuntimeException("must be argv");
        }
        return $parsed;
    }

    /**
    *   setDataFromArgv(short, long)
    *
    *   @param string[] $parsed
    *   @return $this
    */
    protected function setDataFromArgv(array $parsed)
    {
        foreach ($parsed as $prop => $val) {
            $this->$prop = is_bool($val) ? true : $val;
        }
        return $this;
    }

    /**
    *   setOptindDataFromArgv
    *
    *   @return $this
    */
    protected function setOptindDataFromArgv()
    {
        global $argv;

        if (
            count(static::$definedOptindOptions) == 0 ||
            !is_int($this->optind)
        ) {
            return $this;
        }

        array_map(
            function ($def, $val) {
                if (is_null($def)) {
                    throw new RuntimeException("to many optind:{$val}");
                }

                if (is_null($val)) {
                    return;
                }

                if ($def[-1] != ':') {
                    $this->$def = true;
                    return;
                }
                $propName = mb_ereg_replace(':', '', $def);
                $this->$propName = $val;
                return;
            },
            static::$definedOptindOptions,
            array_slice($argv, $this->optind)
        );
        return $this;
    }
}
