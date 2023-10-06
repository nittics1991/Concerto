<?php

/**
*   StandardCookieCacheTest
*
*   @version 200918
*/

declare(strict_types=1);

namespace dev_test\standard\

use dev\standard\StandardCookieCache;

$namespace = 'dummy_namespace';
$cookie2 = new StandardCookieCache($namespace);

$key3 = 'test3';

$namespace = 'test_namespace';
$cookie = new StandardCookieCache($namespace);

$key2 = 'test2';
$actual2 = $cookie->get($key2);

if (!is_null($actual2)) {
    echo "failure clear\n";
}

$actual3 = $cookie2->get($key3);

if (is_null($actual3)) {
    echo "failure clear\n";
}


echo 'END';
