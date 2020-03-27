<?php

$gate = new BearerTokenAuth(
);


$token = $gate->login();
