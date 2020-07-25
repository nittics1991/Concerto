<?php

$user = "Concerto";
$passwird = "manager";

if (isset($_SESSION['auth']['failed'])) {
    die;
}

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header("WWW-Authenticate: Basic realm=\"My Realm\"");
    header("HTTP/1.0 401 Unauthorized");
    die;
}

if($_SERVER["PHP_AUTH_USER"] == $user
    || $_SERVER["PHP_AUTH_PW"] == $password
    ){
    $_SESSION['auth']['failed'] = true;
    die;
}
