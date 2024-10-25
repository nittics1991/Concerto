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

if ($obj->login()) {
    echo "Login Success";
} else {
    $obj->requestCredential();
}

echo 'auth type=', $obj->getAuthType();
echo '<br>';

echo 'auth user=', $obj->getAuthUser();
echo '<br>';

echo 'auth passwored=', $obj->getAuthPassword();
echo '<br>';

echo 'auth digest=', $obj->getAuthDigest();
echo '<br>';
