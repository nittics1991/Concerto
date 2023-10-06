<?php

/**
*   TypeHintCheckerRunner
*
*   @usage php TypeHintCheckerRunner filepath
*   @version 230117
*/

declare(strict_types=1);

namespace tool;

use Throwable;
use tool\TypeHintChecker;

if (empty($argv[1])) {
    echo "usege:php TypeHintCheckerRunner filepath" . PHP_EOL;
    die(1);
}

try {
    $checker = new TypeHintChecker();
    $messages = $checker($argv[1]);

    foreach ($messages as $message) {
        echo $message . PHP_EOL;
    }
} catch (Throwable $e) {
    echo "error:{$argv[1]},{$e->getLine()},{$e->getMessage()}" .
        PHP_EOL;

    error_log(
        var_export($e, true) . PHP_EOL .
            var_export($checker, true) . PHP_EOL,
        3,
        $pwd . DIRECTORY_SEPARATOR . 'TypeHintCheckerRunner.log',
    );

    die(2);
}
