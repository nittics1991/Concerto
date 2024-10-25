<?php

/**
*   app
*
*   @version 240821
*/

declare(strict_types=1);

$tools = [
    'DbgCmd',
    'DumpFunctionList',
    'TypeHintChecker',
];

$pwd = getcwd();

chdir(__DIR__);

set_include_path(
    get_include_path() .
    ';' .
    __DIR__ .
    ';' .
    realpath(implode(
        DIRECTORY_SEPARATOR,
        [
            __DIR__,
            '..',
        ]
    )) .
    ';' .
    realpath(implode(
        DIRECTORY_SEPARATOR,
        [
            __DIR__,
            '..',
            '..',
        ]
    ))
);

require_once realpath(implode(
    DIRECTORY_SEPARATOR,
    [
        __DIR__,
        '..',
        '..',
        '..',
        'vendor',
        'autoload.php',
    ]
));

spl_autoload_register('spl_autoload');

array_shift($argv);

$tool = isset($argv[0]) ?
    ucfirst($argv[0]) : '';

if (in_array($tool, $tools)) {
    require_once
        realpath(implode(
            DIRECTORY_SEPARATOR,
            [
                __DIR__,
                lcfirst($tool),
                ucfirst($tool) . 'Runner.php',
            ],
        ));

    die(0);
}

throw new RuntimeException(
    "tool not found:{$tool}",
);

die(1);
