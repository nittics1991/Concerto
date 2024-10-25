<?php

//header追加&401送信

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

$obj->addHeader(
    'x-test-header',
    "use\nnewline\rspace line",
);

$obj->requestCredential();
