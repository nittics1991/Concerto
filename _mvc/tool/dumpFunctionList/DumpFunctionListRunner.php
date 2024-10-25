<?php

/**
*   DumpFunctionListRunner
*
*   @usage php DumpFunctionListRunner filepath
*   @version 240821
*/

declare(strict_types=1);

namespace tool\dumpFunctionList;

use Throwable;
use tool\dumpFunctionList\DumpFunctionList;

if (empty($argv[1])) {
    echo "usege:php app.cmd dumpFunctionList filepath" . PHP_EOL;
    die(1);
}

try {
    $dumper = new DumpFunctionList();
    $dumper($argv[1]);
} catch (Throwable $e) {
    echo "error:{$argv[1]},{$e->getLine()},{$e->getMessage()}" .
        PHP_EOL;

    error_log(
        print_r($e, true) . PHP_EOL .
            print_r($dumper ?? '', true) . PHP_EOL,
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
