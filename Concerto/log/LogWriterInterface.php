<?php

/**
*   ログライターインターフェース
*
*   @version 150419
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
    public function setFormat($format);

    /**
    *   出力
    *
    *   @param mixed $messages
    */
    public function write($messages);
}
