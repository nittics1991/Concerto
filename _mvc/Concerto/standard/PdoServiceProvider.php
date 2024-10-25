<?php

/**
*   PdoServiceProvider
*
*   @version 200403
*/

declare(strict_types=1);

namespace Concerto\standard;

use Concerto\container\provider\AbstractServiceProvider;
use PDO;

class PdoServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'concertoPdo',
        'symphonyPdo',
    ];

    public function register(): void
    {
        $this->share('concertoPdo', function ($container) {
            $config = $container->get('configSystem');
            $pdo = new PDO(
                $config['database']['default']['dns'],
                $config['database']['default']['user'],
                $config['database']['default']['password']
            );

            $pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
            $pdo->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );
            return $pdo;
        });

        $this->share('symphonyPdo', function ($container) {
            $config = $container->get('configSystem');
            $pdo = new PDO(
                $config['database']['Symphony']['dns'],
                $config['database']['Symphony']['user'],
                $config['database']['Symphony']['password']
            );

            $pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
            $pdo->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );
            return $pdo;
        });
    }
}
