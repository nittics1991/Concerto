<?php

/**
*    HtmlPattern
*
*   @version 181009
*/

declare(strict_types=1);

namespace Concerto\view;

use Concerto\standard\Invokable;

class HtmlPattern implements Invokable
{
    /**
    *   pattern
    *
    *   @var array
    */
    protected $patterns = [
        'Ymd' => '^20\d{6}$',   //yyyymmdd
        'Ym' => '^20\d{4}$',    //yyyymm
        'Hi' => '^\d{2}:\d{2}$',    //hh:ii
        'K' => '^[^｡-ﾟ]*$', //半角カナ禁止
        'KC' => '^[^｡-ﾟ\x00-\x1f\x7f]*$',   //半角カナ　制御記号禁止
        //半角カナ　制御記号　記号禁止
        'KCM' => '^[^｡-ﾟ\x00-\x1f\x7f\x20-\x2f\x3a-\x40\x5b-\x60\x7b-\x7e]*$',
    ];
    
    /**
    *   __construct
    *
    *   @param array $patterns
    */
    public function __construct(array $patterns = [])
    {
        $this->patterns = (array)array_replace($this->patterns, $patterns);
    }
    
    /**
    *   {inherit}
    */
    public function __invoke(...$args)
    {
        $name = $args[0] ?? '';
        return $this->patterns[$name] ?? '';
    }
}
