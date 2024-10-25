<?php

/**
*   ストリーム
*
*   @version 160809
*/

declare(strict_types=1);

namespace candidate\stream;

use InvalidArgumentException;
use RuntimeException;
use candidate\stream\StreamCallbackFilter;

class StreamUtil
{
    /**
    *   フィルタ名空間
    *
    *   @var string
    */
    public const FILTER_NAME = 'Concerto.StreamUtil.';

    /**
    *   定義名リスト
    *
    *   @var string[]
    */
    private static $stack = [];

    /**
    *   定義名リスト取得
    *
    *   @return string[]
    */
    public static function getIdList()
    {
        return static::$stack;
    }

    /**
    *   定義
    *
    *   @param string $id
    *   @return string フィルター名 or false
    */
    public static function register(string $id)
    {
        $result = true;
        $name = StreamUtil::FILTER_NAME . $id;

        if (!in_array($id, static::$stack)) {
            $result = stream_filter_register(
                $name,
                __NAMESPACE__ . '\StreamCallbackFilter'
            );

            if (!$result) {
                throw new RuntimeException("can not register:{$id}");
            }
            static::$stack[] = $id;
        }
        return $name;
    }

    /**
    *   最後尾追加
    *
    *   @param resource $stream
    *   @param mixed $callback 処理
    *   @param string $id ID
    *   @param int $read_write モード
    *   @return resource
    */
    public static function append(
        $stream,
        mixed $callback,
        string $id = '*',
        int $read_write = STREAM_FILTER_ALL
    ) {
        if (!is_resource($stream)) {
            throw new InvalidArgumentException(
                "append(stream, callback, name, read_write)"
            );
        }

        $namespace = StreamUtil::register($id);
        $filter = stream_filter_append(
            $stream,
            $namespace,
            $read_write,
            $callback
        );

        if ($filter === false) {
            throw new RuntimeException("can not append filter");
        }
        return $filter;
    }

    /**
    *   先頭追加
    *
    *   @param resource $stream
    *   @param mixed $callback 処理
    *   @param string $id ID
    *   @param int $read_write モード
    *   @return resource
    */
    public static function prepend(
        $stream,
        mixed $callback,
        string $id = '*',
        int $read_write = STREAM_FILTER_ALL
    ) {
        if (!is_resource($stream)) {
            throw new InvalidArgumentException(
                "append(stream, callback, name, read_write)"
            );
        }

        $namespace = StreamUtil::register($id);
        $filter = stream_filter_prepend(
            $stream,
            $namespace,
            $read_write,
            $callback
        );

        if ($filter === false) {
            throw new RuntimeException("can not prepend filter");
        }
        return $filter;
    }

    /**
    *   削除
    *
    *   @param resource $filter
    *   @return bool
    */
    public static function remove($filter): bool
    {
        if (!is_resource($filter)) {
            throw new InvalidArgumentException("args (stream)");
        }

        $result = stream_filter_remove($filter);

        if ($result === false) {
            throw new RuntimeException("can not remove filter");
        }
        return $result;
    }
}
