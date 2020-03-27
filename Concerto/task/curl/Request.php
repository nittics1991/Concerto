<?php

/**
*   cURL リクエスト
*
*   @version 190510
*/

declare(strict_types=1);

namespace Concerto\task\curl;

use InvalidArgumentException;
use RuntimeException;
use Concerto\task\curl\Response;

class Request
{
    /**
    *   ID
    *
    *   @var string
    **/
    private $id;
    
    /**
    *   ハンドル
    *
    *   @var resource
    **/
    private $handle;
    
    /**
    *   レスポンス
    *
    *   @var Response
    **/
    private $response;
    
    /**
    *   __construct
    *
    *   @param string$id
    *   @param array $options
    *   @throws RuntimeException
    **/
    public function __construct(string $id, array $options = [])
    {
        if (($this->handle = curl_init()) === false) {
            throw new RuntimeException("curl init error");
        }
        $this->id = $id;
        $this->setOpt($options);
    }
    
    /**
    *   オプション設定
    *
    *   @param array $options
    *   @throws InvalidArgumentException
    **/
    private function setOpt(array $options)
    {
        foreach ($options as $key => $val) {
            if ((curl_setopt($this->handle, $key, $val)) == false) {
                throw new InvalidArgumentException(
                    "failed option parameter:{$key}={$val}"
                );
            }
        }
    }
    
    /**
    *   送信
    *
    *   @return bool
    **/
    public function send()
    {
        $successed = true;
        if (($response = curl_exec($this->handle)) === false) {
            $successed =  false;
        }
        
        $this->response = new Response($this->handle, (string)$response);
        curl_close($this->handle);
        return $successed;
    }
    
    /**
    *   ID取得
    *
    *   @return string
    **/
    public function getId()
    {
        return $this->id;
    }
    
    /**
    *   ハンドル取得
    *
    *   @return resource
    **/
    public function getHandle()
    {
        return $this->handle;
    }
    
    /**
    *   レスポンス取得
    *
    *   @return Response
    **/
    public function getResponse()
    {
        return $this->response;
    }
}
