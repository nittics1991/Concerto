<?php

//ini_set('session.gc_probability', '1');
//ini_set('session.gc_divisor', '1');
//ini_set('session.gc_maxlifetime', '1');

session_save_path('/home/user/DL');

//require_once(__DIR__ . DIRECTORY_SEPARATOR . "SessionFileHandler.php");
require_once(__DIR__ . DIRECTORY_SEPARATOR . "FileSessionHandler.php");

//$handler = new Concerto\standard\SessionFileHandler();
$handler = new Concerto\standard\FileSessionHandler();
//$handler = new FileSessionHandler();

session_set_save_handler($handler,false);
//session_set_save_handler($handler,true);


//session_write_close();
//session_start();
//session_write_close();

$user_id = "abcdefgh";
$user_id = "9eajruujagg89go0mivagf11fc";

session_id($user_id);

session_start();

echo "---\n";
var_dump("ID=" . session_id());
echo "---\n";

echo "---\n";
var_dump("data=" , $_SESSION);
echo "---\n";

$_SESSION['a'] = date('YmdHis') . uniqid();
$_SESSION['b'] = date('YmdHis') . uniqid();

//$_SESSION['a'] = 'ABCDEFGH';
//$_SESSION['b'] = 'QWERTYUI';

echo "---\n";
var_dump("data=" , $_SESSION);
echo "---\n";

echo "---\n";
var_dump("ID=" . session_id());
echo "---\n";

//session_regenerate_id(false);
//session_regenerate_id(true);


//echo "---\n";
//var_dump("data=" , $_SESSION);
//echo "---\n";

//echo "---\n";
//var_dump("ID=" . session_id());
//echo "---\n";

session_write_close();


echo "---write_close\n";


@session_start();

echo "---\n";
var_dump("ID=" . session_id());
echo "---\n";

echo "---\n";
var_dump("data=" , $_SESSION);
echo "---\n";






//session_gc();

