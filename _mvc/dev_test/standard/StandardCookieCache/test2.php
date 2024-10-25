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

$actual1 = $cookie->get($key1);

if ($actual1 != $val1) {
    echo "expect=\n";
    var_dump($val1);
    echo "\n";
    echo "actual=\n";
    var_dump($actual1);
    echo "\n";
}

$key2 = 'test2';
$val2 = new \DateTime('2020-01-01');

$actual2 = $cookie->get($key2);

if ($actual2 != $val2) {
    echo "expect=\n";
    var_dump($val2);
    echo "\n";
    echo "actual=\n";
    var_dump($actual2);
    echo "\n";
}

echo 'END';
