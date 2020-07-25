<?php

ini_set('log_errors', 1);
ini_set('log_errors_max_len', 0);
ini_set('error_log', __DIR__ . DIRECTORY_SEPARATOR . 'err.log');

ini_set('display_errors', '0');
error_reporting(E_ALL);

ini_set('date.timezone', 'Asia/Tokyo');

mb_detect_order('UTF-8,SJIS,EUC-JP,JIS,ASCII');
mb_internal_encoding('UTF-8');
mb_regex_encoding('UTF-8');
setlocale(LC_ALL, 'jpn_jpn');

session_start();
