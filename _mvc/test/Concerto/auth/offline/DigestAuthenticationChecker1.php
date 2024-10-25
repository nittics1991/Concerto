<?php

//simple & no password

use Concerto\auth\authentication\DigestAuthentication;
use Concerto\auth\authentication\SimpleAuthUserRepositoryImpl;

require_once realpath('../../bootstrap.php');

$obj = new DigestAuthentication(
    new SimpleAuthUserRepositoryImpl(
        [
            'user1' => 'pass1',
            'user2' => '',
        ]
    ),
    'bbb@example.com'
);

if ($obj->login()) {
    echo "Login Success";
} else {
    $obj->requestCredential();
}
