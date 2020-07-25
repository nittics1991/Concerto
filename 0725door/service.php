<?php

require_once('Post.php');
require_once('User.php');

$post = new Post();

if (!$post->posted()) {
    $view = new View();
    $view->render();
    die;
}

if (!$post->isValid()) {
    throw new RuntimeException(
        "invalid post data"
    );
}

$user = new User($post);
if (!$user->login()) {
    throw new RuntimeException(
        "invalid login"
    );
}

if (!$user->proxy()) {
    throw new RuntimeException(
        "invalid proxy"
    );
}

$url = "https://itcv1800005m.toshiba.local:{$post->port_no}/itc_work1/index.php";
header($url);
