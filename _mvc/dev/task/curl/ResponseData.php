<?php

/**
*   ResponseData
*
*   @version 200520
*/

declare(strict_types=1);

namespace dev\task\curl;

use InvalidArgumentException;

class ResponseData
{
    /**
    *   ID
    *
    *   @var string
    */
    private $id = '';

    /**
    *   ヘッダー
    *
    *   @var string[]
    */
    private $headers = [];

    /**
    *   ボディ
    *
    *   @var string
    */
    private $body = '';

    /**
    *   エラー
    *
    *   @var mixed[]
    */
    private $error = [];

    /**
    *   ハンドル情報
    *
    *   @var mixed[]
    */
    private $info = [];

    /**
    *   __construct
    *
    *   @param RequestData $requestData
    *   @param string $response
    */
    public function __construct(
        RequestData $requestData,
        string $response
    ) {
        $this->id = $requestData->getId();
        $handle = $requestData->getHandle();
        $this->setError($handle);
        $this->setInfo($handle);
        $this->parseResponse($response);
    }

    /**
    *   setError
    *
    *   @param resource $handle
    */
    private function setError($handle)
    {
        $error_no = curl_errno($handle);
        if ($error_no !== 0) {
            $this->error[$error_no] = curl_strerror($error_no);
        }
    }

    /**
    *   setInfo
    *
    *   @param resource $handle
    */
    private function setInfo($handle)
    {
        $info = curl_getinfo($handle);
        if ($info !== false) {
            $this->info = $info;
        }
    }

    /**
    *   parseResponse
    *
    *   @param string $response
    */
    private function parseResponse($response)
    {
        if (
            mb_strtolower($response) === ''
            || !isset($this->info['header_size'])
            || !isset($this->info['size_download'])
        ) {
            return;
        }

        if (strlen($response) == $this->info['size_download']) {
            $this->body = $response;
            return;
        }

        $header = mb_strcut($response, 0, $this->info['header_size']);
        $this->headers = (array)mb_split("\r\n", trim($header));
        $this->body = mb_strcut($response, $this->info['header_size']);
    }

    /**
    *   ID取得
    *
    *   @return string
    */
    public function getId(): string
    {
        return $this->id;
    }

    /**
    *   ヘッダー取得
    *
    *   @return string[]
    */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
    *   ボディ取得
    *
    *   @return string
    */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
    *   エラー取得
    *
    *   @return mixed[]
    */
    public function getError(): array
    {
        return $this->error;
    }

    /**
    *   伝送情報
    *
    *   @return mixed[]
    */
    public function getInfo(): array
    {
        return $this->info;
    }
}
