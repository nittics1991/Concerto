<?php

/**
*   認証インターフェース
*
*   @version 150419
*/

namespace Concerto\auth;

interface AuthInterface
{
    /**
    *   ログイン
    *
    *   @param string id
    *   @param string パスワード
    *   @return bool
    */
    public function login($name, $password);
    
    /**
    *   ログアウト
    */
    public function logout();
}
