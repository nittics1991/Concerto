<?php

use dev\task\curl\Request;
use dev\task\curl\Response;
use dev\task\curl\MultiRequest;



$params = [
    CURLOPT_URL => 'http://itc0028.itc.toshiba.co.jp:8080/itc_develop/index.php',
    CURLOPT_TIMEOUT => 4
    , CURLOPT_CONNECTTIMEOUT => 4
    , CURLOPT_MAXREDIRS => 3
    , CURLOPT_RETURNTRANSFER => 1
    , CURLOPT_FAILONERROR => 1
    , CURLOPT_FOLLOWLOCATION => 1
    , CURLOPT_MAXREDIRS => 10

    , CURLOPT_HEADER => true

];

$multi = new MultiRequest();

for ($i = 0; $i <= 8; $i++) {
    $req = new Request("TEST{$i}", $params);
    $multi->add($req);
}


$multi->send();

$multi_res = $multi->getResponse();

var_dump($multi_res);
echo "<hr>\r\n";
