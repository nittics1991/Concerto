<?php

/**
*   StandardCookieCacheTest
*
*   @version 200918
*/

declare(strict_types=1);

namespace dev_test\standard\

use dev\standard\StandardCookieCache;

$namespace = 'test_namespace';
$cookie = new StandardCookieCache($namespace);

$key1 = 'test1';
$actual1 = $cookie->get($key1);

if (!is_null($actual1)) {
    echo "failure delete\n";
}

$namespace = 'dummy_namespace';
$cookie2 = new StandardCookieCache($namespace);
$key3 = 'test3';
$actual3 = "dummy";

$actual3 = $cookie2->get($key3);

if (is_null($actual3)) {
    echo "deleted data in other namespaces\n";
}

$cookie->clear();

echo 'END';
