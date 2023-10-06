<?php

/**
*   boot
*
*   @version 220427
*/

declare(strict_types=1);

call_user_func(function () {
    $bootFiles = [
        __DIR__ . '/autoload.php',
        __DIR__ . '/iniset.php',




        // __DIR__ . '/container.php',
        //__DIR__ . '/start.php',

        __DIR__ . '/app.php',
    ];

    foreach ($bootFiles as $path) {
        require_once realpath($path);
    }
});
