<?php

/**
*   app
*
*   @version 210324
*/

declare(strict_types=1);

// use Throwable;

register_shutdown_function(
    '_registerShutdownErrorLog',
    realpath(__DIR__ . '/../../log/') .
        DIRECTORY_SEPARATOR .
        'shutdown_' . date('Ymd') . '.log'
);
set_error_handler("_registerErrorException");

try {
    if (PHP_SAPI === 'cli') {
        require_once(realpath(__DIR__ . '/../_console/console.php'));
    } else {
        require_once(realpath(__DIR__ . '/../_router/route.php'));
    }
} catch (\Throwable $e) {
    error_log(
        (function ($e) {
            $url = $_SERVER['REQUEST_URI'] ??
                (
                    isset($_SERVER['argv']) ?
                        implode(' ', $_SERVER['argv']) : ''
                );

            return
                date('Ymd His') . "\n" .
                    "{\n" .
                     $url . "\n" .
                    _expansionException($e) . "\n" .
                    svar_dump($GLOBALS) . "\n" .
                    "}\n";
        })($e),
        3,
        realpath(__DIR__ . '/../../log') .
        DIRECTORY_SEPARATOR .
        'err.log'
    );
}
