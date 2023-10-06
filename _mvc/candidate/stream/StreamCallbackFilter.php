<?php

/**
*   コールバックフィルタ
*
*   @version 210609
*/

declare(strict_types=1);

namespace candidate\stream;

use Exception;
use php_user_filter;

class StreamCallbackFilter extends php_user_filter
{
    /**
    *   ストリーム
    *
    *   @var resource
    */
    public $stream;

    /**
    *   クローズフラグ
    *
    *   @var bool
    */
    protected $closed = true;

    /**
    *   callback
    *
    *   @var callable
    */
    protected $callback;

    /**
    *   イニシャライズ
    *
    *   @return bool
    */
    public function onCreate(): bool
    {
        $this->closed = false;

        if (!is_callable($this->params)) {
            return false;
        }

        $this->callback = $this->params;
        return true;
    }

    /**
    *   シャットダウン
    *
    */
    public function onClose(): void
    {
        $this->closed = true;
        unset($this->callback);
    }

    /**
    *   フィルタ
    *
    *   @param resource $in
    *   @param resource $out
    *   @param int $consumed data length
    *   @param bool $closing 終了処理中
    *   @return int 処理結果
    */
    public function filter($in, $out, &$consumed, bool $closing): int
    {
        $data = '';
        while ($bucket = stream_bucket_make_writeable($in)) {
            $data .= $bucket->data;
            $consumed += $bucket->datalen;
        }

        if ($this->closed) {
            return PSFS_FEED_ME;
        }

        if ($data !== '') {
            try {
                $data = call_user_func($this->callback, $data);
                $bucket = stream_bucket_new($this->stream, $data);

                if ($bucket !== false) {
                    stream_bucket_append($out, $bucket);
                }
            } catch (Exception $e) {
                $this->onClose();
                return PSFS_ERR_FATAL;
            }
        }

        if ($closing) {
            $this->closed = true;
        }
        return PSFS_PASS_ON;
    }
}
