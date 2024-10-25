<?php

use Concerto\standard\session\SessionFactory;

/*
ini_set('session.gc_probability', '1');
ini_set('session.gc_divisor', '1');
ini_set('session.gc_maxlifetime', '1');
*/

session_save_path('/home/user/DL');

require_once(__DIR__ . DIRECTORY_SEPARATOR . "SessionFactory.php");

$factory = new SessionFactory();

$session = $factory->build();

