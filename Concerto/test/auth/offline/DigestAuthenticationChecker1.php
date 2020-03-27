<?php

//simple & no password

use Concerto\auth\authentication\DigestAuthentication;
use Concerto\auth\authentication\SimpleAuthUserRepositoryImpl;

require_once realpath('../../bootstrap.php');

$obj = new DigestAuthentication(
    new SimpleAuthUserRepositoryImpl(
        [
            'aaa' => 'AAA',
            'bbb' => 'BBB',
            'ccc' => 'CCC',
            'ddd' => '',
        ]
    ),
    'bbb@example.com'
);

$obj->login();

echo "Login Success";
