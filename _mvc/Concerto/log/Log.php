<?php

/**
*   ログ
*
*   @version 230927
*/

declare(strict_types=1);

namespace Concerto\log;

use InvalidArgumentException;
use Psr\Log\{
    InvalidArgumentException as LogException,
    LogLevel,
    LoggerInterface,
};
use Concerto\log\{
    LogInterface,
    LogWriterInterface,
};

class Log implements LoggerInterface, LogInterface
{
    /**
    *   @var int
    */
    public const DEBUG = 100;
    public const INFO = 200;
    public const NOTICE = 250;
    public const WARNING = 300;
    public const ERROR = 400;
    public const CRITICAL = 500;
    public const ALERT = 550;
    public const EMERGENCY = 600;

    /**
    *   @var int[]
    */
    private static array $levelmap = [
        LogLevel::DEBUG => self::DEBUG,
        LogLevel::INFO => self::INFO,
        LogLevel::NOTICE => self::NOTICE,
        LogLevel::WARNING => self::WARNING,
        LogLevel::ERROR => self::ERROR,
        LogLevel::CRITICAL => self::CRITICAL,
        LogLevel::ALERT => self::ALERT,
        LogLevel::EMERGENCY => self::EMERGENCY
    ];

    /**
    *   @var LogWriterInterface[]
    */
    private array $writers = [];

    /**
    *   @var int
    */
    private int $limit;

    /**
    *   __construct
    *
    *   @param LogWriterInterface $writer
    *   @param int|string $limit
    */
    public function __construct(
        LogWriterInterface $writer,
        int | string $limit = 999
    ) {
        $this->writers[] = $writer;

        $this->setLimit($limit);
    }

    /**
    *   制限値設定
    *
    *   @param int|string $limit
    *   @return void
    */
    private function setLimit(
        int | string $limit
    ): void {
        if (is_int($limit)) {
            $this->limit = $limit;
            return;
        }

        if (
            is_string($limit) &&
            array_key_exists($limit, self::$levelmap)
        ) {
            $this->limit = self::$levelmap[$limit];

            return;
        }

        throw new InvalidArgumentException(
            "limit not defined"
        );
    }

    /**
    *   addWriter
    *
    *   @param LogWriterInterface $writer
    *   @return void
    */
    public function addWriter(
        LogWriterInterface $writer
    ): void {
        $this->writers[] = $writer;
    }

    /**
    *   @inheritDoc
    */
    public function write(
        mixed $messages
    ): void {
        if ($this->depth($messages) === 0) {
            $list = [array($messages)];
        } elseif ($this->depth($messages) === 1) {
            $list = [$messages];
        } elseif ($this->depth($messages) === 2) {
            $list = $messages;
        } else {
            throw new InvalidArgumentException(
                "log error"
            );
        }

        $cnt = 0;

        foreach ($this->writers as $writer) {
            $writer->write($list[$cnt]);

            $cnt++;
        }
    }

    /**
    *   次元数計算
    *
    *   @param mixed $target
    *   @param int $depth 次元数
    *   @return int 次元数
    */
    private function depth(
        mixed $target,
        int $depth = 0
    ): int {
        if (!is_array($target)) {
            return $depth;
        }

        $tmp = [];

        $depth++;

        foreach ($target as $val) {
            $tmp[] = $this->depth($val, $depth);
        }

        return (int)max($tmp);
    }

    /**
    *   変数展開
    *
    *   @param string $message
    *   @param mixed[] $context
    *   @return string
    */
    private function interpolate(
        string $message,
        array $context
    ): string {
        $replace = [];
        foreach ($context as $key => $val) {
            if (
                !is_array($val) &&
                (
                    !is_object($val) ||
                    method_exists($val, '__toString')
                )
            ) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        return strtr($message, $replace);
    }

    /**
    *   @inheritDoc
    */
    public function emergency(
        string | \Stringable $message,
        array $context = []
    ): void {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
    *   @inheritDoc
    */
    public function alert(
        string | \Stringable $message,
        array $context = []
    ): void {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
    *   @inheritDoc
    */
    public function critical(
        string | \Stringable $message,
        array $context = []
    ): void {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
    *   @inheritDoc
    */
    public function error(
        string | \Stringable $message,
        array $context = []
    ): void {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
    *   @inheritDoc
    */
    public function warning(
        string | \Stringable $message,
        array $context = []
    ): void {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
    *   @inheritDoc
    */
    public function notice(
        string | \Stringable $message,
        array $context = []
    ): void {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
    *   @inheritDoc
    */
    public function info(
        string | \Stringable $message,
        array $context = []
    ): void {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
    *   @inheritDoc
    */
    public function debug(
        string | \Stringable $message,
        array $context = []
    ): void {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
    *   @inheritDoc
    */
    public function log(
        $level,
        string | \Stringable $message,
        array $context = []
    ): void {
        if (is_int($level)) {
            $lvl = $level;
        } elseif (
            is_string($level) &&
            array_key_exists($level, self::$levelmap)
        ) {
            $lvl = self::$levelmap[$level];
        } else {
            throw new LogException("level not defined");
        }

        $outdata = is_string($message) ?
            $message : $message->__toString();

        if (count($context) > 0) {
            $outdata = $this->interpolate($outdata, $context);
        }

        if ($lvl <= $this->limit) {
            $this->write($outdata);
        }
    }
}
