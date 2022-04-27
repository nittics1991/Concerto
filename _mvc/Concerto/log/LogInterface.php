<?php

/**
*   ログインターフェース
*
*   @version 210916
*/

declare(strict_types=1);

namespace Concerto\log;

interface LogInterface
{
    /**
    *   出力
    *
    *  @param mixed $messages
    */
    public function write(
        mixed $messages
    );
}
