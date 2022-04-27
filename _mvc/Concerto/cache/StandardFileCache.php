<?php

/**
*   StandardFileCache
*
*   @version 220322
*/

declare(strict_types=1);

namespace Concerto\cache;

use DateTime;
use RuntimeException;
use Psr\SimpleCache\CacheInterface;
use Psr\Log\LoggerInterface;
use Concerto\cache\FileCache;

class StandardFileCache extends FileCache implements
    CacheInterface
{
    /**
    *   @var string
    */
    protected string $log_format =
        "%s class=%s method=%s key=%s result=%s";
    /**
    *   @var string[]
    *   @example datetime|class|function|file|line|key|result
    */
    protected array $log_variables = [
        'datetime',
        'class',
        'function',
        'key',
        'result',
    ];

    /**
    *   @var ?LoggerInterface
    */
    protected ?LoggerInterface $logger;

    /**
    *   __construct
    *
    *   @param ?string $dir
    *   @param ?LoggerInterface $logger
    */
    public function __construct(
        ?string $dir = null,
        ?LoggerInterface $logger = null,
    ) {
        $this->logger = $logger;
        $temp_dir = $dir ??
            sys_get_temp_dir() .
            DIRECTORY_SEPARATOR .
            'StandardFileCache';

        parent::__construct(
            $temp_dir,
        );

        if (file_exists($temp_dir)) {
            return;
        }

        $result = mkdir($temp_dir, 0777, true);
        if ($result === false) {
            throw new RuntimeException(
                "cache directory create error",
            );
        }
    }

    /**
    *   {inherit}
    */
    public function get(
        string $key,
        mixed $default = null
    ): mixed {
        return $this->decorate(
            'parent::get',
            func_get_args(),
        );
    }

    /**
    *   {inherit}
    */
    public function set(
        string $key,
        mixed $value,
        null|int|\DateInterval $ttl = null
    ): bool {
        return (bool)$this->decorate(
            'parent::set',
            func_get_args(),
        );
    }

    /**
    *   {inherit}
    */
    public function delete(
        string $key
    ): bool {
        return (bool)$this->decorate(
            'parent::delete',
            func_get_args(),
        );
    }

    /**
    *   {inherit}
    */
    public function clear(): bool
    {
        return (bool)$this->decorate(
            'parent::clear',
            func_get_args(),
        );
    }

    /**
    *   decorate
    *
    *   @param callable|string $callback
    *   @param array $arguments
    *   @return mixed
    */
    protected function decorate(
        callable|string $callback,
        array $arguments,
    ): mixed {
        $result = call_user_func_array(
            $callback,
            $arguments,
        );
        $this->log((bool)$result);
        return $result;
    }

    /**
    *   log
    *
    *   @param bool $result
    *   @return void
    */
    protected function log(
        bool $result,
    ): void {
        static $logger;

        if ($logger === null) {
            $logger = $this->logger ??
                new class () {
                    public function info($message)
                    {
                        error_log(
                            $message,
                            3,
                            sys_get_temp_dir() .
                            DIRECTORY_SEPARATOR .
                            'StandardFileCache.log',
                        );
                    }
                };
        }

        $datetime = (new DateTime())
            ->format(DateTime::ATOM);

        $trace = debug_backtrace(0, 3);
        $trace2 = $trace[2] ?? [];
        extract($trace2);
        $key = $args[0] ?? '';
        $result = (string)$result;

        $message = vsprintf(
            $this->log_format,
            compact($this->log_variables),
        );

        $logger->info($message . PHP_EOL);
    }
}
