<?php

/**
*   RequestData
*
*   @version 200520
*/

declare(strict_types=1);

namespace dev\task\curl;

use InvalidArgumentException;
use RuntimeException;
use dev\task\curl\Response;

class RequestData
{
    /**
    *   ID
    *
    *   @var string
    */
    private $id = '';

    /**
    *   ハンドル
    *
    *   @var resource
    */
    private $handle;

    /**
    *   オプション
    *
    *   @var mixed[]
    */
    private $options = [];

    /**
    *   __construct
    *
    *   @param string $id
    *   @param mixed[] $options
    *   @throws RuntimeException
    */
    public function __construct(string $id, array $options = [])
    {
        if (($this->handle = curl_init()) === false) {
            throw new RuntimeException("curl init error");
        }
        $this->id = $id;
        $this->options = $options;
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
            if ((curl_setopt($this->handle, $key, $val)) == false) {
                throw new InvalidArgumentException(
                    "failed option parameter:{$key}={$val}"
                );
            }
        }
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
    *   ハンドル取得
    *
    *   @return resource
    */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
    *   レスポンス取得
    *
    *   @return mixed[]
    */
    public function getOptions(): array
    {
        return $this->options;
    }
}
