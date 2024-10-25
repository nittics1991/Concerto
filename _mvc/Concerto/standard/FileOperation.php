<?php

/**
*   ファイル操作
*
*   @version 230116
*/

declare(strict_types=1);

namespace Concerto\standard;

use InvalidArgumentException;

class FileOperation
{
    /**
    *   clearTempDir
    *
    *   @param string $path
    *   @param int $expire_day
    *   @return void
    */
    public function clearTempDir(
        string $path,
        int $expire_day = 0
    ): void {
        $filterd = mb_convert_encoding(
            $path,
            'SJIS-WIN',
            'UTF-8'
        );

        if (!is_string($filterd)) {
            throw new InvalidArgumentException(
                "filter error:{$path}",
            );
        }

        $before_day = -1 * $expire_day;

        exec(
            "forfiles /P {$filterd} /M *.* " .
            "/D {$before_day} /C \"cmd /c del @file\""
        );
    }
}
