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
$val3 = "dummy";
$cookie2->set($key3, $val3);


$namespace = 'test_namespace';
$cookie = new StandardCookieCache($namespace);

$key1 = 'test1';
$cookie->delete($key1);

echo 'END';
