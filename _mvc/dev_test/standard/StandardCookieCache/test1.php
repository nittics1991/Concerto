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
$val1 = new \ArrayObject([
    'prop_b' => false,
    'prop_i' => 1234,
    'prop_f' => 56.789,
    'prop_s' => 'dummy1',
]);

$cookie->set($key1, $val1);

$key2 = 'test2';
$val2 = new \DateTime('2020-01-01');

$cookie->set($key2, $val2);

echo 'END';
