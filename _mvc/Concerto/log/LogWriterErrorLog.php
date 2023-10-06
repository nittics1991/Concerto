<?php

/**
*   ログライターerror_log function
*
*   @version 221207
*/

declare(strict_types=1);

namespace Concerto\log;

use InvalidArgumentException;
use RuntimeException;
use Concerto\log\LogWriterInterface;

class LogWriterErrorLog implements LogWriterInterface
{
    /**
    *   @var string
    */
    private string $stream = 'err.log';

    /**
    *   @var string
    */
    private string $format = "%s";

    /**
    *   __construct
    *
    *   @param mixed[] $config 設定値
    *   @param string $name
    */
    public function __construct(
        mixed $config,
        string $name = 'default'
    ) {
        if (is_array($config['log'][$name])) {
            foreach ($config['log'][$name] as $key => $val) {
                if (
                    isset($this->$key) &&
                    !empty($val)
                ) {
                    $this->$key = $val;
                }
            }
        }
    }

    /**
    *   @inheritDoc
    *
    *   @param string $format 書式(printfと同じ)
    *   @return static
    */
    public function setFormat(
        string $format
    ): static {
        $this->format = $format;

        return $this;
    }

    /**
    *   @inheritDoc
    *
    *  @param mixed $messages メッセージ or メッセージ配列(vsprintf引数)
    *  @return void
    */
    public function write(
        mixed $messages
    ): void {
        $args = !is_array($messages) ?
            [$messages] : $messages;

        if ($this->getFormatElementCount() !== count($args)) {
            throw new InvalidArgumentException(
                "write error"
            );
        }

        if (
            !error_log(
                vsprintf($this->format, $args),
                3,
                $this->stream
            )
        ) {
            throw new RuntimeException(
                "write error"
            );
        }
    }

    /**
    *   フォーマット要素数
    *
    *   @return int
    */
    public function getFormatElementCount(): int
    {
        return (
            mb_strlen($this->format)
            - mb_strlen(
                (string)mb_ereg_replace(
                    '%[b,c,d,e,E,f,F,g,G,o,s,u,x,X]',
                    '',
                    $this->format
                )
            )
        ) / 2;
    }

    /**
    *   ログファイルパス設定
    *
    *   @return static
    */
    public function setLogFilePath(
        string $path
    ): static {
        $this->stream = $path;

        return $this;
    }
}
