<?php

//no password

use Concerto\auth\authentication\BasicAuthentication;
use Concerto\auth\authentication\SimpleAuthenticationGate;

require_once realpath('../../bootstrap.php');

$userId = 'user1';
$password = '';

$obj = new BasicAuthentication(
    new SimpleAuthenticationGate(
        $userId,
        $password
    )
);

$obj->login();

echo "Login Success";
