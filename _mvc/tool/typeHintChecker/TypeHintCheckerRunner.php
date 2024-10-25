<?php

/**
*   TypeHintCheckerRunner
*
*   @usage php TypeHintCheckerRunner filepath
*   @version 240821
*/

declare(strict_types=1);

namespace tool\typeHintChecker;

use Throwable;
use tool\typeHintChecker\TypeHintChecker;

if (empty($argv[1])) {
    echo "usege:php app.php typeHintChecker filepath" . PHP_EOL;
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
        print_r($e, true) . PHP_EOL .
            print_r($checker ?? '', true) . PHP_EOL,
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
