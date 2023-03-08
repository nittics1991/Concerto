<?php

require_once(__DIR__ . DIRECTORY_SEPARATOR . "SessionFileHandler.php");

session_save_path('/home/user/DL');

//ini_set('session.gc_probability', '1');
//ini_set('session.gc_divisor', '1');
//ini_set('session.gc_maxlifetime', '1');

//$handler = new Concerto\standard\SessionFileHandler();

//session_set_save_handler($handler,false);

session_start();

echo "---\n";
var_dump("ID=" . session_id());
echo "---\n";

//echo "---\n";
//var_dump("data=" , $_SESSION);
//echo "---\n";

$_SESSION['a'] = 'AAA';
$_SESSION['b'] = 'BBB';

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

//session_write_close();

//echo "---cloased\n";

//session_gc();

