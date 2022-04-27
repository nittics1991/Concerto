<?php

/**
*   タスク管理
*
*   @version 190510
*/

declare(strict_types=1);

namespace dev\task;

use RuntimeException;
use dev\task\curl\MultiRequestHandler;
use dev\task\curl\RequestData;
use dev\task\curl\ResponseData;

class TaskManagerStandard
{
    /**
    *   タイムアウト
    *
    *   @var int
    */
    protected $timeout;

    /**
    *   MultiRequest
    *
    *   @var MultiRequest
    */
    protected $multi;

    /**
    *    __construct
    *
    *   @param int $timeout
    */
    public function __construct(int $timeout = 120)
    {
        $this->timeout = $timeout;
        $this->multi = new MultiRequestHandler(
            [
                CURLMOPT_MAXCONNECTS => 5,
                CURLMOPT_MAX_HOST_CONNECTIONS => 5,
                CURLMOPT_MAX_TOTAL_CONNECTIONS => 5,
            ]
        );
    }

    /**
    *    タスク追加
    *
    *   @param string $name
    *   @param string $url
    *   @param mixed[] $params
    *   @return $this
    */
    public function add(string $name, string $url, array $params = [])
    {
        $opt = [
            CURLOPT_TIMEOUT => $this->timeout,  //タイムアウト
            CURLOPT_CONNECTTIMEOUT => $this->timeout,   //タイムアウト
            CURLOPT_RETURNTRANSFER => 1,    //curl_exec()を文字列return
            CURLOPT_FAILONERROR => 1,   //HTTP 400以上はERROR判定
            CURLOPT_HEADER => true, //ヘッダを取得
            CURLOPT_FOLLOWLOCATION => 1,    //Locationヘッダを再帰取得
            CURLOPT_MAXREDIRS => 10,    //Locationヘッダを再帰取得最大
        ];

        $opt = array_replace($opt, $params);
        $opt[CURLOPT_URL] = $url;
        $this->multi->add(new RequestData($name, $opt));
        return $this;
    }

    /**
    *    スレッド開始
    *
    *   @return bool 実行結果
    */
    public function start()
    {
        $result = $this->multi->send();
        return $result && $this->checkResponseCode();
    }

    /**
    *    レスポンスコード確認
    *
    *   @return bool
    */
    private function checkResponseCode(): bool
    {
        foreach ($this->getResponse() as $response) {
            $headers = $response->getHeaders();

            if (
                isset($headers['http_code'])
                && $headers['http_code'] != '200'
            ) {
                return false;
            }
        }
        return true;
    }

    /**
    *    エラー情報取得
    *
    *   @return array
    */
    public function getError(): array
    {
        return $this->multi->getErrors();
    }

    /**
    *    レスポンス取得
    *
    *   @return array [Response, ...]
    */
    public function getResponse(): array
    {
        return $this->multi->getResponses();
    }
}
