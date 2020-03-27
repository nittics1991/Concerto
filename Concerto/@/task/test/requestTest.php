<?php
use Concerto\task\curl\Request;
use Concerto\task\curl\Response;
use Concerto\task\curl\MultiRequest;

require_once('../_function/ComFunc.php');

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

$req = new Request('TEST1', $params);

$sql = $req->send();
$res = $req->getResponse();
$sql = $res->getBody();
var_dump($sql);
echo "<hr>\r\n";

$sql = $res->getHeaders();
var_dump($sql);
echo "<hr>\r\n";


$sql = $res->getStatus();
var_dump($sql);
echo "<hr>\r\n";

$sql = $res->getError();
var_dump($sql);
echo "<hr>\r\n";

$sql = $res->getInfo();
var_dump($sql);
echo "<hr>\r\n";
