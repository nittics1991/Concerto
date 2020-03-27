<?php

return array(
    'database' => array (
        'default' => array(
            'adapter' => 'pgsql',
            'params' => array(
                'host' => 'localhost',
                'port' => '5432',
                'dbname' => 'postgres',
                'user' => 'postgres',
                'password' => 'manager'
            )
        )
    )   //END database
    , 'log' => array(
        'default' => array(
            'stream' => 'err.log',
            'format' => '%s:%s' . PHP_EOL
        )
    )   //END log
);
