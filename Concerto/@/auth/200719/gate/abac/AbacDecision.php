<?php

/**
*   AbacDecision
*
*   @version 200718
*/

namespace Concerto\gate\abac;

class AbacDecision
{
    /**
    *   許可
    */
    public const PERMIT = 'Permit';
    
    /**
    *   不許可
    */
    public const DENY = 'Deny';
    
    /**
    *   ルール無し
    */
    public const NOTAPPLICABLE = 'NotApplicable';
    
    /**
    *   評価エラー
    */
    public const INDETERMINATE = 'Indeterminate';
}
