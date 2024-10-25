<?php

/**
*   cURL マルチリクエスト
*
*   @version 210903
*/

declare(strict_types=1);

namespace Concerto\task\curl;

use CurlMultiHandle;
use InvalidArgumentException;
use RuntimeException;
use Concerto\task\curl\Response;

class MultiRequest
{
    /**
    *   タイムアウト
    *
    *   @var int
    */
    private $timeout = 120;

    /**
    *   同時接続最大数
    *
    *   @var int
    */
    private $max_connect = 5;

    /**
    *   リトライ回数
    *
    *   @var int
    */
    private $retry_count = 10;

    /**
    *   マルチハンドル
    *
    *   @var CurlMultiHandle
    */
    private CurlMultiHandle $multi;

    /**
    *   レスポンス
    *
    *   @var array Response
    */
    private $responses = [];

    /**
    *   ハンドル
    *
    *   @var array resource
    */
    private $handles = [];

    /**
    *   errors
    *
    *   @var array
    */
    private $errors = [];

    /**
    *   __construct
    *
    *   @param int $timeout
    *   @param array $options
    *   @param int $max_connect
    *   @param int $retry_count
    *   @throws RuntimeException
    */
    public function __construct(
        int $timeout = 120,
        array $options = [],
        int $max_connect = 5,
        int $retry_count = 10
    ) {
        $multi_handle = curl_multi_init();
        if ($multi_handle === false) {
            throw new RuntimeException("curl init error");
        }
        $this->multi = $multi_handle;

        if ($timeout > 0 && $timeout <= 600) {
            $this->timeout = $timeout;
        }

        if ($max_connect > 0 && $max_connect <= 10) {
            $this->max_connect = $max_connect;
        }

        if ($retry_count > 0 && $retry_count <= 600) {
            $this->retry_count = $retry_count;
        }

        $this->setOpt($options);
    }

    /**
    *   オプション設定
    *
    *   @param array $options
    *   @throws InvalidArgumentException
    */
    private function setOpt(array $options)
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
    *   @param Request $request
    *   @return $this
    */
    public function add(Request $request)
    {
        $this->handles[$request->getId()] = $request;
        return $this;
    }

    /**
    *   送信
    *
    *   @return bool
    *   @throws RuntimeException
    */
    public function send()
    {
        $handles_set = array_chunk($this->handles, $this->max_connect);

        $successed = true;
        foreach ($handles_set as $handles) {
            foreach ($handles as $handle) {
                $result = curl_multi_add_handle(
                    $this->multi,
                    $handle->getHandle()
                );
                if ($result != 0) {
                    throw new RuntimeException(
                        "cURL multi add failed:" .
                        $handle->getId() .
                        ":{$result}"
                    );
                }
            }
            $successed = $this->execute() && $successed;
        }
        curl_multi_close($this->multi);
        return $successed;
    }

    /**
    *   送信実行
    *
    *   @return bool
    *   @throws RuntimeException
    */
    public function execute()
    {
        $result = true;
        $status = curl_multi_exec($this->multi, $running);

        if (!$running || $status !== CURLM_OK) {
            throw new RuntimeException("cURL run failed:{$status}");
        }

        $cnt = 0;
        do {
            switch (curl_multi_select($this->multi, $this->timeout)) {
                //failed select
                case -1:
                    usleep(10);
                    curl_multi_exec($this->multi, $running);
                    continue 2;
                //timeout
                case 0:
                    if ($cnt < $this->retry_count) {
                        //php VerUpで最初の数回,0が発生するようになった
                        $cnt++;
                        usleep(10);
                        curl_multi_exec($this->multi, $running);
                        continue 2;
                    }
                    throw new RuntimeException(
                        "cURL multi timeout:{$this->timeout}"
                    );
                    break 2;
                default:
                    $result = $this->processCrulActivity() && $result;
            }
        } while ($running);
        return $result;
    }

    /**
    *   processCrulActivity
    *
    *   @return bool
    */
    private function processCrulActivity()
    {
        curl_multi_exec($this->multi, $running);
        $result = true;
        $queue = null;

        do {
            if ($info = curl_multi_info_read($this->multi, $queue)) {
                $id = $this->getHandleKey($info['handle']);

                if ($info['result'] != CURLE_OK) {
                    $result = false;
                }

                $this->responses[$id] = new Response(
                    $info['handle'],
                    curl_multi_getcontent($info['handle'])
                );

                $removed = curl_multi_remove_handle(
                    $this->multi,
                    $info['handle']
                );
                if ($removed != 0) {
                    $this->errors[$id][] = $removed;
                    $result = false;
                }
                curl_close($info['handle']);
            }
        } while ($queue);
        return $result;
    }

    /**
    *   ハンドルキー
    *
    *   @return string|null id
    */
    private function getHandleKey($handle)
    {
        foreach ($this->handles as $id => $request) {
            if ($request->getHandle() == $handle) {
                return $id;
            }
        }
        return null;
    }

    /**
    *   レスポンス取得
    *
    *   @return array Response
    */
    public function getResponse()
    {
        return $this->responses;
    }

    /**
    *   ハンドル取得
    *
    *   @return array resource
    */
    public function getHandles()
    {
        return $this->handles;
    }
}
