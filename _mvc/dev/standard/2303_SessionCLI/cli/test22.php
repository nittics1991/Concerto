<?php

/*
ini_set('session.gc_probability', '1');
ini_set('session.gc_divisor', '1');
ini_set('session.gc_maxlifetime', '1');
*/

session_save_path('/home/user/DL');

require_once(__DIR__ . DIRECTORY_SEPARATOR . "FileSessionHandler.php");

$handler = new Concerto\standard\FileSessionHandler();

session_set_save_handler($handler,false);

//$user_id = "9eajruujagg89go0mivagf11fc";
$user_id = "abcdefgh";

session_id($user_id);

/*
echo "---\n";
var_dump("status_start_a1=" , session_status());
echo "---\n";
 */

session_start();

/*
echo "---\n";
var_dump("status_start_b1=" , session_status());
echo "---\n";
 */

echo "---\n";
var_dump("ID=" . session_id());
echo "---\n";

echo "---\n";
var_dump("prev_data=" , $_SESSION);
echo "---\n";

$_SESSION['a'] = date('YmdHis');
$_SESSION['b'] = uniqid('',true);

echo "---\n";
var_dump("data=" , $_SESSION);
echo "---\n";

echo "---\n";
var_dump("ID=" . session_id());
echo "---\n";

echo "---write_regenerate\n";

@session_regenerate_id(false);
//@session_regenerate_id(true);


echo "---\n";
var_dump("data=" , $_SESSION);
echo "---\n";

echo "---\n";
var_dump("ID=" . session_id());
echo "---\n";

session_write_close();


echo "---write_close\n";

//warning
@session_start();

echo "---\n";
var_dump("ID=" . session_id());
echo "---\n";

echo "---\n";
var_dump("data=" , $_SESSION);
echo "---\n";


echo "---cookie_params\n";

echo "---\n";
var_dump("cookie_params=" , session_get_cookie_params());
echo "---\n";


echo "---module_name\n";

echo "---\n";
var_dump("module_name=" , session_module_name());
echo "---\n";

echo "---name\n";

echo "---\n";
var_dump("name=" , session_name());
echo "---\n";

//session_write_close();

//session_gc();

