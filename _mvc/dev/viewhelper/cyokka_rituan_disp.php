<?php

declare(strict_types=1);

use cyokka_rituan2\model\CyokkaRituanDispFactory;

require_once('login.php');

$pdo = _getDBConSingleton($configSystem);
$factory = new CyokkaRituanDispFactory($pdo);
$controller = $factory->getControllerModel();

$controller->setQuery();

if ($controller->isValidPost()) {
    try {
        switch ($controller->act) {
            case 'insert':
            case 'update':
            case 'delete':
                $controller->setData();
                break;
        }
    } catch (Exception $e) {
        $log = _getLogSingleton($configSystem);
        $log->write([date('Ymd His'), _expansionException($e)]);
    }
}

$controller->buildData();
$view = $factory->getViewDispModel($controller->toArray());
$view();
