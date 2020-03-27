<?php

/**
*   CookieEnv
*
*   @version 160316
*/

declare(strict_types=1);

namespace Concerto;

use Exception;
use Concerto\standard\Cookie;
use Concerto\standard\StringUtil;

final class CookieEnv
{
    /**
    *   登録
    *
    *   @param string $namespace SESSION名前空間
    *   @param array $config cookie設定
    *   @param mixed $data データ
    */
    public static function set(string $namespace, array $config, $data)
    {
        $cookie = new Cookie($config);
        $cookie->$namespace = StringUtil::jsonEncode($data);
    }
    
    /**
    *   削除
    *
    *   @param string $namespace SESSION名前空間
    *   @param array $config cookie設定
    */
    public static function delete(string $namespace, array $config)
    {
        $cookie = new Cookie($config);
        unset($cookie->$namespace);
    }
    
    /**
    *   取得
    *
    *   @param string $namespace SESSION名前空間
    *   @param array $config 設定
    *   @return mixed
    */
    public static function get(string $namespace, array $config)
    {
        $cookie = new Cookie($config);
        try {
            return (array)json_decode($cookie->$namespace);
        } catch (Exception $e) {
            return [];
        }
    }
}
