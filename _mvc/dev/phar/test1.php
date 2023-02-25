<?php

$file = __DIR__ . "/prophecy-master.zip"; 

var_dump(file_exists($file));


    // "zip://{$file}#composer.json",
    //"zip://{$file}",
    //"zip://{$file}#composer.json",
    //"compress.zlib://{$file}#composer.json",


#zipアーカイブは読めるが,アーカイブ内のファイルが読めない
$contents = file_get_contents(
    "compress.zlib://{$file}",
);


var_dump($contents);


/*
$file = __DIR__ . "/../prophecy-phpunit.phar"; 

$p = new Phar($file, 0, 'my.phar');
echo $p->getStub();
*/


/*

require_once __DIR__ . "/../prophecy-phpunit.phar";

$contents = file_get_contents(
    'phar://propecy-phpunit/composer.json'
);
    
var_dump($contents);
 */


