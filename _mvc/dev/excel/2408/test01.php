<?php

/**
*   ExcelArchive
*
*   @version 240724
*/

declare(strict_types=1);

namespace dev\excel;

function getColumnName(int $n): string {
    if ($n < 1 || $n > 16384) {
        throw new \InvalidArgumentException('Column index must be between 1 and 16384.');
    }

    $columnName = '';
    while ($n > 0) {
        $n--;
        $columnName = chr(65 + $n % 26) . $columnName;
        $n = (int)($n / 26);
    }

    return $columnName;
}













