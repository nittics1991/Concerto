<?php

/**
*   ログライターインターフェース
*
*   @version 210818
*/

declare(strict_types=1);

namespace Concerto\log;

interface LogWriterInterface
{
    /**
    *   フォーマット
    *
    *   @param string $format
    */
    public function setFormat(
        string $format
    );

    /**
    *   出力
    *
    *   @param mixed $messages
    */
    public function write(
        mixed $messages
    );
}
