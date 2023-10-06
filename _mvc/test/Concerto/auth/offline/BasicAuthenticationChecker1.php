<?php

//simple

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

$obj->login();

echo "Login Success";
