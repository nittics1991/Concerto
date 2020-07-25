<?php

require_once('Post.php');
require_once('Post.php');

$post = new Post();

if (!$post->posted()) {
    $view = new View();
    $view->render();
    die;
}

if (!$post->isValid()) {
    $_SESSION['auth']['failed'] = true;
    die;
}

$user = new User($post);
if (!$user->login()) {
    $_SESSION['auth']['failed'] = true;
    die;
}

if (!$user->proxy()) {
    $_SESSION['auth']['failed'] = true;
    die;
}

$url = "https://itcv1800005m.toshiba.local:{$post->port_no}/itc_work1/index.php";
header($url);
