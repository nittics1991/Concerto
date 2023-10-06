<?php

/**
*    HtmlPattern
*
*   @version 210827
*/

declare(strict_types=1);

namespace candidate\view;

use Concerto\standard\Invokable;

class HtmlPattern implements Invokable
{
    /**
    *   pattern
    *
    *   @var string[]
    */
    protected $patterns = [
        'ascii' => '^[\x21-\x7e]+$',
        'bumon' => '^[A-Z0-9]{4,5}$',
        'cyuban' => '^[A-Z,0-9]{7,8}$',
        'cyumon' => '^(K|G|J)[A-Z,0-9]{3}[0-9]{5}((\-)([0-9]{2}))*$',
        'email' => '^[0-9a-z\.]+@(glb\.)?toshiba\.co\.jp$',
        'nendo' => '^20\d{2}(K|S)$',
        'postAdr' => '^\d{3}-\d{4}$',
        'tanto' => '^\d{5}ITC$',
        'tel' => '^\d{2,4}-\d{2,4}-\d{4}$',
        'user' => '^[0-9,A-Z,a-z]{8}$',
        'url' => '^https://.+$',

        //yyyy-mm-dd
        'date' => '^20\d{2}-[01]\d-[0-3]\d$',
        //yyyymmdd
        'ymd' => '^20\d{2}[01]\d[0-3]\d$',
        //yyyymm
        'ym' => '^20\d{2}[01]\d$',

        //hh:ii
        'time' => '^[0-2]\d:[0-5]\d$',
        //hhii
        'hi' => '^[0-2]\d[0-5]\d$',

        //半角カナ禁止
        '_K' => '^[^｡-ﾟ]*$',
        //半角カナ　制御記号禁止
        '_KC' => '^[^｡-ﾟ\x00-\x1f\x7f]*$',
        //半角カナ　制御記号　記号禁止
        '_KCM' => '^[^｡-ﾟ\x00-\x1f\x7f\x20-\x2f\x3a-\x40\x5b-\x60\x7b-\x7e]*$',
        //半角カナ　制御記号　記号禁止 改行許可
        '_KCMr' => '^[^｡-ﾟ\x00-\x09\x0b-\x0c\x0e-\x1f\x7f\x20-\x2f\x3a-\x40\x5b-\x60\x7b-\x7e]*$',

    ];

    /**
    *   __construct
    *
    *   @param string[] $patterns
    */
    public function __construct(array $patterns = [])
    {
        $this->patterns = (array)array_replace(
            $this->patterns,
            $patterns
        );
    }

    /**
    *   @inheritDoc
    */
    public function __invoke(...$values): mixed
    {
        $name = $values[0] ?? '';
        return $this->patterns[$name] ?? '';
    }

    /**
    *   @inheritDoc
    */
    public function __call(string $name, array $arguments): mixed
    {
        return $this->patterns[$name] ?? '';
    }

    /**
    *   @inheritDoc
    */
    public function __get(string $name): mixed
    {
        return $this->patterns[$name] ?? '';
    }
}
