<?php

require_once(__DIR__ . DIRECTORY_SEPARATOR . "SessionFileHandler.php");

session_save_path('/home/user/DL');

$handler = new Concerto\standard\SessionFileHandler();

session_set_save_handler($handler,false);

session_start();

echo "---\n";
var_dump($_SESSION);
echo "---\n";




