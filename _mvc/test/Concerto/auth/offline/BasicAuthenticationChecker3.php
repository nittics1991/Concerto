<?php

//set responce

use Concerto\auth\authentication\BasicAuthentication;
use Concerto\auth\authentication\SimpleAuthenticationGate;

require_once realpath('../../bootstrap.php');

$userId = 'user1';
$password = 'pass1';

$obj = new BasicAuthentication(
    new SimpleAuthenticationGate(
        $userId,
        $password
    )
);

session_start();

$obj->setResponse(function () {
    if ($_SESSION['test.count'] > 3) {
        echo 'Login Failure';
        exit;
    }

    $_SESSION['test.count'] = isset($_SESSION['test.count']) ?
        ++$_SESSION['test.count'] : 0;

    session_write_close();

    header('WWW-Authenticate: Basic');
    header('HTTP/1.0 401 Unauthorized');
    exit;
});

$obj->login();

echo "Login Success";
