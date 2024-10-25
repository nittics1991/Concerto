<?php

/**
*   DumpFunctionListRunner
*
*   @usage php DumpFunctionListRunner filepath
*   @version 240821
*/

declare(strict_types=1);

namespace tool\dbgCmd;

use Throwable;
use tool\dbgCmd\DbgCmd;

try {
    $dbgCmd = new DbgCmd();
    $dbgCmd($argv);
} catch (Throwable $e) {
    echo "error:{$argv[1]},{$e->getLine()},{$e->getMessage()}" .
        PHP_EOL;

    error_log(
        print_r($e, true) . PHP_EOL .
            print_r($dbgCmd ?? '', true) . PHP_EOL,
        3,
        implode(
            DIRECTORY_SEPARATOR,
            [
                __DIR__,
                '..',
                basename(__FILE__) . '.log',
            ],
        ),
    );

    die(2);
}
