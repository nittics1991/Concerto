<?php

return [
    'database' => [
        'default' => [
            'adapter' => 'pgsql',
            'params' => [
                'host' => 'localhost',
                'port' => '5432',
                'dbname' => 'postgres',
                'user' => 'postgres',
                'password' => 'manager'
            ]
        ]
    ]   //END database
    , 'log' => [
        'default' => [
            'stream' => 'err.log',
            'format' => '%s:%s' . PHP_EOL
        ]
    ]   //END log
];
