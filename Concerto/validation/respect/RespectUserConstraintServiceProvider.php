<?php

/**
*   RespectUserConstraintServiceProvider
*
*   @version 180711
*   @see ユーザ設定ルールはRules,Exceptionsという名前のフォルダに作成する
*/

declare(strict_types=1);

namespace Concerto\validation\respect;

use Concerto\container\provider\AbstractDirectoryServiceProvider;

class RespectUserConstraintServiceProvider extends AbstractDirectoryServiceProvider
{
    /**
    *   {inherit}
    *
    */
    protected $subDirName = 'Rules';
    protected $prefixId = 'validation';
}
