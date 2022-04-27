<?php

/**
*   マルチバイトエンコードストリームフィルタ
*
*   @version 220123
*/

declare(strict_types=1);

namespace candidate\stream;

use php_user_filter;
use RuntimeException;

/**
*   @usage
*       MbEncodeFilter::register();
*       stream_filter_append(
*           $stream,
*           MbEncodeFilter.$to_encoding/$from_encoding",
*           $read_write,
*           $sub_char
*       );
*
*   resource $stream The stream to filter.
*   string $to_encoding The encoding to convert to.
*   string $from_encoding The encoding to convert from.
*       Optional, defaults to mb_internal_encoding()
*   int $read_write
*       See http://php.net/manual/en/function.stream-filter-append.php
*   string|int $sub_char The substitute character to use.
*       Optional, defaults to mb_substitute_character()
*/
class MbEncodeFilter extends php_user_filter
{
    /**
    *   不正文字コードチェック用
    *
    */
    public const NON_CHARACTER = "\xFF\xFF";

    /**
    *   変換後エンコード
    *
    *   @var string
    */
    private $to_encoding;

    /**
    *   変換前エンコード
    *
    *   @var string
    */
    private $from_encoding;

    /**
    *   変更前無効文字コード変換方法
    *
    *   @var string|int|null
    */
    private $prev_mb_substitute_character;

    /**
    *   バッファ
    *
    *   @var string
    */
    private $buffer;

    /**
    *   イニシャライズ
    *
    *   @return bool
    */
    public function onCreate()
    {
        $conversion_part = substr($this->filtername, 15);
        $conversion_part = explode('/', $conversion_part);

        $to_encoding = $conversion_part[0];
        $from_encoding = isset($conversion_part[1]) ?
            $conversion_part[1] : mb_internal_encoding();

        $encodings = mb_list_encodings();
        $aliases = array_map('mb_encoding_aliases', $encodings);

        $valid_encodings = array_reduce($aliases, 'array_merge', $encodings);

        if (
            !in_array($to_encoding, $valid_encodings) ||
            !in_array($from_encoding, $valid_encodings)
        ) {
            return false;
        }

        $this->prev_mb_substitute_character = mb_substitute_character();

        if (is_int($this->params) || is_string($this->params)) {
            mb_substitute_character($this->params);
        }

        $this->to_encoding = $to_encoding;
        $this->from_encoding = $from_encoding;

        return true;
    }

    /**
    *   シャットダウン
    *
    */
    public function onClose(): void
    {
        mb_substitute_character($this->prev_mb_substitute_character);
    }

    /**
    *   フィルタ
    *
    *   @param resource $in
    *   @param resource $out
    *   @param int $consumed datalen
    *   @param bool $closing in stream shutdown flug
    *   @return int
    */
    public function filter($in, $out, &$consumed, $closing)
    {
        $buffer = &$this->buffer;

        while ($bucket = stream_bucket_make_writeable($in)) {
            $encoded_data = $buffer . $bucket->data;
            $valid_chars  = $this->truncateInvalidCharacters($encoded_data);
            $buffer = substr($encoded_data, strlen($valid_chars));

            $decoded_data = $this->convert($valid_chars);

            $bucket->data = $decoded_data;
            $consumed  = $consumed + $bucket->datalen;

            stream_bucket_append($out, $bucket);
        }

        if ($closing && !empty($buffer)) {
            $stream = isset($this->stream) && is_resource($this->stream) ?
                $this->stream : fopen('php://memory', 'r');

            if ($stream === false) {
                throw new RuntimeException(
                    "stream error"
                );
            }

            $remaining = $this->convert($buffer);
            $bucket = stream_bucket_new($stream, $remaining);
            $buffer = '';

            stream_bucket_append($out, $bucket);
        }

        if (!empty($buffer)) {
            return PSFS_FEED_ME;
        }

        return PSFS_PASS_ON;
    }

    /**
    *   不正文字削除
    *
    *   @param string $data
    *   @return string
    */
    private function truncateInvalidCharacters(string $data): string
    {
        $padded_data = $data . self::NON_CHARACTER;
        return mb_strcut(
            $padded_data,
            0,
            strlen($data),
            $this->from_encoding
        );
    }

    /**
    *   エンコード変換
    *
    *   @param string $data
    *   @return string
    */
    private function convert(string $data): string
    {
        return mb_convert_encoding(
            $data,
            $this->to_encoding,
            $this->from_encoding
        );
    }

    /**
    *   フィルタ登録
    *
    */
    public static function register(): void
    {
        stream_filter_register('MbEncodeFilter.*', __CLASS__);
    }
}
