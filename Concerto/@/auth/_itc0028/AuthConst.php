<?php

/**
*   認証定数
*
*   @version 160322
*
**/

namespace Concerto\auth;

final class AuthConst
{
    /**
    *   ログイン状態
    *
    *   @var integer
    */
    const DATAEMPTY = 3;        //user or password empty
    const AUTHENTICATED = 2;    //認証済
    const SUCCESS = 1;          //成功
    const FAILURE = 0;          //失敗
}
