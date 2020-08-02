<?php

/**
*   年FiscalYearObject
*
*   @version 200802
*/

declare(strict_types=1);

namespace Concerto\datetime;

use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;

class FiscalYearObject implements DateTimeInterface
{
    /**
    *   datetime
    *
    *   @var DateTimeInterface
    */
    protected DateTimeInterface $datetime;
    
    
    
    
    /**
    *   format書式
    *
    *   @var string
    */
    protected int $default_format = 'F';
    
    
    
    
    /**
    *   開始月
    *
    *   @var int
    */
    protected int $start_month = 4;
    
    /**
    *   __construct
    *
    *   @param DateTimeInterface $datetime
    *   @param DateTimeZone $timezone
    */
    public function __construct(
         string $datetime = 'now'
        DateTimeZone $timezone = null
    ) {
        $this->datetime = new DateTimeImmutable(
            $datetime,
            $timezone ??= date_default_timezone_get()
        );
    }
    
    /**
    *   createFromDateCode
    *
    *   @param string $code
    *   @param int $start_month
    *   @param DateTimeZone $timezone
    *   @return DateTimeInterface
    */
    public static function createFromDateCode(
        string $code,
        int $start_month = 4,
        DateTimeZone $timezone = null
    ) :DateTimeInterface {
        
        
        
        
        
        return new static(
            $date_string,
            $timezone ??= date_default_timezone_get()
        );
    }
    
    
    //interfaceとして外だし?
    /**
    *   codeToDateString
    *
    *   @param string $code
    *   @return string
    */
    protected function codeToDateString(
        string $code
    ) :string  {
        if (!mb_ereg_match('', $code)) {
            throw new InvalidArgumentException(
                "invalid code"
            );
        
        
    }
    
    
    
    
    
    
    
    
    /**
    *   {inherit}
    *
    */
    public function __call(
        string $name,
        array $arguments
    ) {
        return call_user_func_array(
            [$this->datetime, $name],
            $arguments
        );
    }
    
    /**
    *   {inherit}
    *
    */
    public static function __callStatic(
        string $name,
        array $arguments
    ) {
        return call_user_func_array(
            [$this->datetime, $name],
            $arguments
        );
    }
    
    //////////////////////////////////
    
    
    





    /**
    *   上期月
    *
    *   @var array
    */
    private static $kami = ['04', '05', '06', '07', '08', '09'];
    
    /**
    *   下期月
    *
    *   @var array
    */
    private static $simo = ['10', '11', '12', '01', '02', '03'];
    
    /**
    *   現在年度
    *
    *   @return string 現在年度
    */
    public static function getPresentNendo(): string
    {
        $today = getdate();
        
        if (($today['mon'] >= 10) && ($today['mon'] <= 12)) {
            return $today['year'] . 'S';
        }
        
        if (($today['mon'] >= 1) && ($today['mon'] <= 3)) {
            return ($today['year'] - 1) . 'S';
        }
        return $today['year'] . 'K';
    }
    
    /**
    *   指定年度の次年度(半期単位)
    *
    *   @param ?string $kb_nendo 年度(yyyyK or yyyyS) 省略時は現年度指定とする
    *   @return string|false 次年度
    */
    public static function getNextNendo(?string $kb_nendo = null)
    {
        $kb_nendo_tmp = (is_null($kb_nendo)) ?
            static::getPresentNendo() : $kb_nendo;
        
        if (!preg_match('/^[0-9]{4}(K|S)$/', $kb_nendo_tmp)) {
            return false;
        }
        
        $yyyy = mb_substr($kb_nendo_tmp, 0, 4);
        $half = mb_substr($kb_nendo_tmp, 4, 1);
        if ($half == 'K') {
            return $yyyy . 'S';
        }
        return ((int)$yyyy + 1) . 'K';
    }
    
    /**
    *   指定年度の前年度(半期単位)
    *
    *   @param ?string $kb_nendo 年度(yyyyK or yyyyS) 省略時は現年度指定とする
    *   @return string|false 前年度 or false
    */
    public static function getPreviousNendo(?string $kb_nendo = null)
    {
        $kb_nendo_tmp = (is_null($kb_nendo)) ?
            static::getPresentNendo() : $kb_nendo;
        
        if (!preg_match('/^[0-9]{4}(K|S)$/', $kb_nendo_tmp)) {
            return false;
        }
        
        $yyyy = mb_substr($kb_nendo_tmp, 0, 4);
        $half = mb_substr($kb_nendo_tmp, 4, 1);
        
        if ($half == 'K') {
            return ((int)$yyyy - 1) . 'S';
        }
        return $yyyy . 'K';
    }
    
    /**
    *   指定年度のn期後
    *
    *   @param string $kb_nendo 年度(yyyyK or yyyyS)
    *   @param int $diff n期後
    *   @return string|false
    */
    public static function addNendo(string $kb_nendo, int $diff)
    {
        if (!preg_match('/^[0-9]{4}(K|S)$/', $kb_nendo) || !is_int($diff)) {
            return false;
        }
        
        $yyyy = mb_substr($kb_nendo, 0, 4);
        $half = mb_substr($kb_nendo, 4, 1);
        
        $div = floor(($diff / 2));
        $mod = $diff % 2;
        $yyyy += $div;
        
        if ($half == 'K') {
            if ($mod != 0) {
                $half = 'S';
            }
        } else {
            if ($mod != 0) {
                $half = 'K';
                $yyyy += 1;
            }
        }
        return "{$yyyy}{$half}";
    }
    
    /**
    *   年度記号＝＞年度全角
    *
    *   @param string $kb_nendo 年度(yyyyK or yyyyS)
    *   @return string|false 年度(ｙｙｙｙ年上期 or ｙｙｙｙ年下期) or false
    *
    *   @example 2013K => ２０１３年上期
    */
    public static function nendoCodeToZn(string $kb_nendo)
    {
        if (!preg_match('/^[0-9]{4}(K|S)$/', $kb_nendo)) {
            return false;
        }
        
        if (mb_substr($kb_nendo, 4, 1) == 'K') {
            return mb_convert_kana(mb_substr($kb_nendo, 0, 4), 'N') . '年上期';
        }
        return mb_convert_kana(mb_substr($kb_nendo, 0, 4), 'N') . '年下期';
    }
    
    /**
    *   年度全角＝＞年度記号
    *
    *   @param string $kb_nendo_zn 年度(ｙｙｙｙ年上期 or ｙｙｙｙ年下期)
    *   @return string|false 年度(yyyyK or yyyyS) or false
    *
    *   @example ２０１３年上期 => 2013K
    */
    public static function nendoZnToCode(string $kb_nendo_zn)
    {
        if (mb_strlen($kb_nendo_zn) != 7) {
            return false;
        }
        
        $yyyy = mb_convert_kana(mb_substr($kb_nendo_zn, 0, 4), 'n');
        
        if (!preg_match('/^[0-9]{4}$/', $yyyy)) {
            return false;
        }
        
        if (mb_substr($kb_nendo_zn, 5, 2) == '上期') {
            return $yyyy . 'K';
        }
        
        if (mb_substr($kb_nendo_zn, 5, 2) == '下期') {
            return $yyyy . 'S';
        }
        return false;
    }
    
    /**
    *   年度内年月
    *
    *   @param ?string $kb_nendo 年度(yyyyK or yyyyS) 省略時は現年度指定とする
    *   @return array 年月
    */
    public static function getNendoyyyymm(?string $kb_nendo = null): array
    {
        $kb_nendo_tmp = (is_null($kb_nendo)) ?
            static::getPresentNendo() : $kb_nendo;
        
        if (!preg_match('/^[0-9]{4}(K|S)$/', $kb_nendo_tmp)) {
            return [];
        }
        $yyyymm = [];
        
        if (mb_substr($kb_nendo_tmp, 4, 1) == 'K') {
            for ($i = 0; $i < 6; $i++) {
                $yyyymm[$i] = mb_substr($kb_nendo_tmp, 0, 4)
                    . static::$kami[$i];
            }
        } else {
            for ($i = 0; $i < 3; $i++) {
                $yyyymm[$i] = mb_substr($kb_nendo_tmp, 0, 4)
                    . static::$simo[$i];
            }
            
            for ($i = 3; $i < 6; $i++) {
                $yyyymm[$i] = ((int)mb_substr($kb_nendo_tmp, 0, 4) + 1)
                    . static::$simo[$i];
            }
        }
        return $yyyymm;
    }
    
    /**
    *   年度内月
    *
    *   @param ?string $kb_nendo 年度(yyyyK or yyyyS) 省略時は現年度指定とする
    *   @return array 月
    */
    public static function getNendomm(?string $kb_nendo = null): array
    {
        $kb_nendo_tmp = (is_null($kb_nendo)) ?
            static::getPresentNendo() : $kb_nendo;
        
        if (!preg_match('/^[0-9]{4}(K|S)$/', $kb_nendo_tmp)) {
            return [];
        }
        
        if (mb_substr($kb_nendo_tmp, 4, 1) == 'K') {
            return static::$kami;
        }
        return static::$simo;
    }
    
    /**
    *   年月=>年度
    *
    *   @param string $yyyymm 年月
    *   @return string|false 年度(yyyyK or yyyyS) or false
    */
    public static function getyyyymmToNendo(string $yyyymm)
    {
        if (!preg_match('/^[0-9]{6}$/', $yyyymm)) {
            return false;
        }
        
        $yyyy = mb_substr($yyyymm, 0, 4);
        $mm = mb_substr($yyyymm, 4, 2);
        
        if (($mm >= '01') && ($mm <= '03')) {
            return ((int)$yyyy - 1) . "S";
        }
        
        if (($mm >= '04') && ($mm <= '09')) {
            return $yyyy . "K";
        }
        
        if (($mm >= '10') && ($mm <= '12')) {
            return $yyyy . "S";
        }
        return false;
    }
    
    /**
    *   年度=>開始年月・終了年月
    *
    *   @param string $kb_nendo 年度(yyyyK or yyyyS)
    *   @return array array(yyyymm, yyyymm)
    */
    public static function getNendoPeriod(string $kb_nendo): array
    {
        if (!preg_match('/^[0-9]{4}(K|S)$/', $kb_nendo)) {
            return [];
        }
        
        $yyyymm = FiscalYear::getNendoyyyymm($kb_nendo);
        return [$yyyymm[0], $yyyymm[5]];
    }
    
    /**
    *   指定期間の年度のコレクション
    *
    *   @param string $kb_nendo_s
    *   @param string $kb_nendo_e
    *   @return array [['kb_nendo' => '', 'nm_nendo' => ''], ...]
    **/
    public static function getNendoPeriodCollection(
        string $kb_nendo_s,
        string $kb_nendo_e
    ): array {
        if (!preg_match('/^[0-9]{4}(K|S)$/', $kb_nendo_s)) {
            return [];
        }
        
        if (!preg_match('/^[0-9]{4}(K|S)$/', $kb_nendo_e)) {
            return [];
        }
        
        if ($kb_nendo_e >= $kb_nendo_s) {
            $current = $kb_nendo_s;
            $end = $kb_nendo_e;
            $reverse = false;
        } else {
            $current = $kb_nendo_e;
            $end = $kb_nendo_s;
            $reverse = true;
        }
        
        do {
            $items['kb_nendo'] = $current;
            $items['nm_nendo'] = self::nendoCodeToZn($current);
            $result[] = $items;
            $current = (string)self::getNextNendo($current);
        } while ($current <= $end);
        
        if ($reverse) {
            krsort($result);
        }
        return $result;
    }
    
    /**
    *   会計年度の期差
    *
    *   @param string $baseNendo
    *   @param string $targetNendo
    *   @return int
    **/
    public static function diff(string $baseNendo, string $targetNendo): int
    {
        if ($baseNendo == $targetNendo) {
            return 0;
        }
        
        $baseYear = (int)mb_substr($baseNendo, 0, 4);
        $baseKi = mb_substr($baseNendo, 4, 1);
        
        $targetYear = (int)mb_substr($targetNendo, 0, 4);
        $targetKi = mb_substr($targetNendo, 4, 1);
        
        if ($baseKi == $targetKi) {
            return ($targetYear - $baseYear) * 2
            ;
        }
        
        $diff = ($targetYear - $baseYear) * 2;
        return $baseKi == 'K' ? $diff + 1 : $diff - 1;
    }
}
