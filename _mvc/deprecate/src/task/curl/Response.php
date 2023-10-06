<?php

/**
*   cURL レスポンス
*
*   @version 210903
*/

declare(strict_types=1);

namespace Concerto\task\curl;

use CurlHandle;
use InvalidArgumentException;

class Response
{
    /**
    *   ヘッダー
    *
    *   @var array
    */
    private $headers = [];

    /**
    *   ボディ
    *
    *   @var string
    */
    private $body;

    /**
    *   送信結果
    *
    *   @var bool
    */
    private $status;

    /**
    *   エラー
    *
    *   @var array
    */
    private $error = [];

    /**
    *   ハンドル情報
    *
    *   @var array
    */
    private $info = [];

    /**
    *   コンストラクタ
    *
    *   @param CurlHandle $handle
    *   @param string $response
    *   @throws InvalidArgumentException
    */
    public function __construct(
        CurlHandle $handle,
        string $response
    ) {
        $this->status = mb_strtolower($response) != 'false';
        $this->setErrorAndInfo($handle);
        $this->parseResponse($response);
    }

    /**
    *   エラー及びハンドル情報設定
    *
    *   @param CurlHandle $handle
    */
    private function setErrorAndInfo($handle)
    {
        $this->error[curl_errno($handle)] = curl_error($handle);

        if (($info = curl_getinfo($handle)) !== false) {
            $this->info = $info;
        }
    }

    /**
    *   ヘッダー・ボディ分割
    *
    *   @param string $response
    */
    private function parseResponse($response)
    {
        if (
            mb_strtolower($response) === 'false' ||
            !isset($this->info['header_size']) ||
            !isset($this->info['size_download'])
        ) {
            return;
        }

        if (strlen($response) == $this->info['size_download']) {
            $this->body = $response;
            return;
        }

        $header = mb_strcut($response, 0, $this->info['header_size']);
        $headers = mb_split("\r\n", trim($header));

        if ($headers === false) {
            return;
        }

        $this->headers = $headers;
        $this->body = mb_strcut($response, $this->info['header_size']);
    }

    /**
    *   ヘッダ取得
    *
    *   @return array
    */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
    *   ボディ取得
    *
    *   @return string
    */
    public function getBody()
    {
        return $this->body;
    }

    /**
    *   送信結果取得
    *
    *   @return bool
    */
    public function getStatus()
    {
        return $this->status;
    }

    /**
    *   エラー取得
    *
    *   @return array
    */
    public function getError()
    {
        return $this->error;
    }

    /**
    *   ハンドル情報取得
    *
    *   @return array
    */
    public function getInfo()
    {
        return $this->info;
    }
}
