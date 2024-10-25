<?php

/**
*   タスク管理
*
*   @version 200914
*/

declare(strict_types=1);

namespace Concerto\task;

use RuntimeException;
use Concerto\task\curl\MultiRequest;
use Concerto\task\curl\Request;
use Concerto\task\curl\Response;

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
        $this->multi = new MultiRequest();
    }

    /**
    *    タスク追加
    *
    *   @param string $name
    *   @param string $url
    *   @param array $params
    *   @return $this
    */
    public function add(string $name, string $url, array $params = [])
    {
        $opt = [
            CURLOPT_TIMEOUT => $this->timeout,  //タイムアウト
            CURLOPT_CONNECTTIMEOUT => $this->timeout,   //タイムアウト
            CURLOPT_RETURNTRANSFER => 1,    //curl_exec()を文字列return
            CURLOPT_FAILONERROR => 1,   //HTTP 400以上はERROR判定
            CURLOPT_FOLLOWLOCATION => 1,    //Locationヘッダを再帰取得
            CURLOPT_MAXREDIRS => 10,    //Locationヘッダを再帰取得最大
            CURLOPT_SSL_VERIFYPEER => false,    //サーバー証明書検証なし
        ];

        $opt = array_replace($opt, $params);
        $opt[CURLOPT_URL] = $url;
        $this->multi->add(new Request($name, $opt));
        return $this;
    }

    /**
    *    スレッド開始
    *
    *   @return bool 実行結果
    */
    public function start()
    {
        return $this->multi->send();
    }

    /**
    *    エラー情報取得
    *
    *   @return array
    */
    public function getError()
    {
        $error = [];
        foreach ((array)$this->multi->getResponse() as $id => $response) {
            $error[$id] = $response->getError();
        }
        return $error;
    }

    /**
    *    レスポンス取得
    *
    *   @return array [Response, ...]
    */
    public function getResponse()
    {
        return $this->multi->getResponse();
    }
}
