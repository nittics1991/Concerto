<?php

/**
*   AbacAction
*
*   @version 200718
*/

namespace Concerto\gate\abac;

class AbacAction
{
    /**
    *   読取
    */
    public const READ = 1;
    
    /**
    *   更新
    */
    public const UPDATE = 10;
    
    /**
    *   作成
    */
    public const CREATE = 100;
    
    /**
    *   削除
    */
    public const DELETE = 1000;
    
    /**
    *   読者
    */
    public const READER = 1;
    
    /**
    *   利用者
    */
    public const COMMENTER = 11;
    
    /**
    *   作者
    */
    public const EDITOR = 111;
    
    /**
    *   全て
    */
    public const ALL = 1111;
}
