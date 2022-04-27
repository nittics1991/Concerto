<?php

/**
*   MultiRequestHandler
*
*   @version 200522
*/

declare(strict_types=1);

namespace dev\task\curl;

use InvalidArgumentException;
use RuntimeException;
use dev\task\curl\{
    RequestData,
    ResponseData
};

class MultiRequestHandler
{
    /**
    *   マルチハンドル
    *
    *   @var resource
    */
    private $multi;

    /**
    *   options
    *
    *   @var mixed[]
    */
    private $options = [];

    /**
    *   リクエスト
    *
    *   @var resource[]
    */
    private $requests = [];

    /**
    *   レスポンス
    *
    *   @var Response[]
    */
    private $responses = [];

    /**
    *   errors
    *
    *   @var mixed[]
    */
    private $errors = [];

    /**
    *   __construct
    *
    *   @throws RuntimeException
    */
    public function __construct(array $options = [])
    {
        $this->options = $options;

        $this->multi = curl_multi_init();
        if ($this->multi === false) {
            throw new RuntimeException("curl init error");
        }

        $this->setOptions($options);
    }

    /**
    *   オプション設定
    *
    *   @param mixed[] $options
    *   @throws InvalidArgumentException
    */
    private function setOptions(array $options)
    {
        foreach ($options as $key => $val) {
            if (curl_multi_setopt($this->multi, $key, $val) == false) {
                throw new InvalidArgumentException(
                    "failed option parameter:{$key}={$val}"
                );
            }
        }
    }

    /**
    *   リクエスト追加
    *
    *   @param RequestData $request
    *   @return $this
    */
    public function add(RequestData $requestData)
    {
        $this->requests[$requestData->getId()] = $requestData;
        return $this;
    }

    /**
    *   送信
    *
    *   @return bool
    */
    public function send(): bool
    {
        $this->addHandles();
        $result = $this->doSend();
        $this->removeHandles();
        curl_multi_close($this->multi);

        return $result;
    }

    /**
    *   ハンドル設定
    *
    */
    private function addHandles()
    {
        foreach ($this->requests as $request) {
            $result = curl_multi_add_handle(
                $this->multi,
                $request->getHandle()
            );
        }
    }

    /**
    *   送信&受信
    *
    *   @return bool
    *   @throws RuntimeException
    */
    private function doSend(): bool
    {
        $result = true;
        $queue;

        do {
            $status = curl_multi_exec($this->multi, $running);

            if ($status !== CURLM_OK) {
                throw new RuntimeException("cURL run failed:{$status}");
            }

            if (curl_multi_select($this->multi) === -1) {
                continue;
            }

            $info = curl_multi_info_read($this->multi, $queue);

            if ($info === false) {
                continue;
            }

            $id = $this->getIdFromRequest($info['handle']);
            $requestData = $this->requests[$id];

            $this->responses[$id] = new ResponseData(
                $requestData,
                curl_multi_getcontent($info['handle'])
            );

            $error = ($this->responses[$id])->getError();
            if (!empty($error)) {
                $this->errors[$id][] = $error;
                $result = false;
            }
        } while ($running);

        return $result;
    }

    /**
    *   ハンドル解除
    *
    */
    private function removeHandles()
    {
        foreach ($this->requests as $request) {
            $result = curl_multi_remove_handle(
                $this->multi,
                $request->getHandle()
            );
        }
    }

    /**
    *   ハンドルでリクエストからID検索
    *
    *   @param resource $handle
    *   @return string|null id
    */
    private function getIdFromRequest($handle)
    {
        foreach ($this->requests as $id => $request) {
            if ($request->getHandle() === $handle) {
                return $id;
            }
        }
        return null;
    }

    /**
    *   リクエスト取得
    *
    *   @return array resource
    */
    public function getRequests()
    {
        return $this->requests;
    }

    /**
    *   レスポンス取得
    *
    *   @return array Response
    */
    public function getResponses()
    {
        return $this->responses;
    }

    /**
    *   エラー取得
    *
    *   @return resource[]
    */
    public function getErrors()
    {
        return $this->errors;
    }
}
