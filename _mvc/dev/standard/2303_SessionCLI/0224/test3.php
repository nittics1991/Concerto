<?php

use Concerto\cache\SessionCache;

require_once(__DIR__ . "/../../../vendor/autoload.php");

session_save_path('/home/user/DL');

//ini_set('session.gc_probability', '1');
//ini_set('session.gc_divisor', '1');
//ini_set('session.gc_maxlifetime', '1');

$data = array_combine(
    range(0,9),
    array_map(
        fn($val) => uniqid(),
        range(0,9),
    ),
);


$session = new SessionCache(
    'test1',
);


var_dump($session);







var_dump($data);






