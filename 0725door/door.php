<?php

require_once('iniset.php');
require_once('auth.php');

try {
    require_once('service.php');
} catch (Throwable $e) {
    ob_start();
    
    echo date('Ymd His') . '\\n';
    var_dump($e);
    echo '----------------------------------\\n\\n';
    
    $message = ob_get_contents();
    ob_end_clean();
    
    error_log($data, 0, $message)
    die;
}
