<?php

/**
*   iniset
*
*   @version 220427
*/

declare(strict_types=1);

//error report
ini_set('error_reporting', E_ALL);

ini_set('display_errors', '1');

ini_set('log_errors', '1');

ini_set('log_errors_max_len', '0');

ini_set(
    'error_log',
    realpath(__DIR__ . '/../../log') .
        DIRECTORY_SEPARATOR .
        'phperr_' . date('Ymd') . '.log'
);


//resource
ini_set('memory_limit', '256M');

ini_set('max_execution_time', '120');

ini_set('opcache.revalidate_freq', '60');


//string
ini_set('default_charset', 'UTF-8');

ini_set('default_charset', 'UTF-8');

mb_detect_order('UTF-8,SJIS,EUC-JP,JIS,ASCII');

mb_internal_encoding('UTF-8');

mb_regex_encoding('UTF-8');

setlocale(LC_ALL, 'jpn_jpn');


//date
ini_set('date.timezone', 'Asia/Tokyo');


//session
ini_set(
    'session.save_path',
    realpath(__DIR__ . '/../../tmp')
);

ini_set('session.use_strict_mode', '1');

ini_set('session.cookie_httponly', '1');

ini_set('session.cookie_secure', '1');

ini_set('session.gc_divisor', '1');

ini_set('session.cookie_samesite', 'Strict');

ini_set('session.sid_length', '48');

ini_set('session.sid_bits_per_character', '6');
