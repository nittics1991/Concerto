<?php

/**
*   LogServiceProvider
*
*   @version 170216
*/

declare(strict_types=1);

namespace Concerto\log;

use Concerto\container\provider\AbstractServiceProvider;
use Concerto\log\Log;
use Concerto\log\LogInterface;
use Concerto\log\LogWriterErrorLog;
use Concerto\log\LogWriterInterface;

class LogServiceProvider extends AbstractServiceProvider
{
    /**
    *   @var array
    */
    protected $provides = [
      LogInterface::class,
      LogWriterInterface::class,
      Log::class,
      LogWriterErrorLog::class,
    ];

    /**
    *   register
    *
    */
    public function register(): void
    {
        $this->share(LogInterface::class, function ($container) {
            return $container->get(Log::class);
        });

        $this->share(LogWriterInterface::class, function ($container) {
            return $container->get(LogWriterErrorLog::class);
        });

        $this->share(Log::class, function ($container) {
            return new Log($container->get(LogWriterInterface::class));
        });

        $this->share(LogWriterErrorLog::class, function ($container) {
            $config = $container->get('configSystem');
            return new LogWriterErrorLog($config);
        });
    }
}
