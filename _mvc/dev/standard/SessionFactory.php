<?php

/**
*   SessionFactory
*
*   @version 230309 
*/

declare(strict_types=1);

namespace Concerto\standard\session;

use Concerto\standard\session\FileSessionHandler;

class SessionFactory
{
    /**
    *   SAPI CLI判定文字列
    *
    *   @var string[]
    */
    private const SAPI_CLIS = ['cli', 'phpdbg'];

    /**
    *   build
    *
    *   @param ?string $namespace SESSION空間名
    *   @param ?array $data セッションデータ
    *   @param ?int $max_life_time 最大ライフタイム
    */
    public function __construct(
        ?string $namespace = null,
        ?array $data = null,
        ?int $max_life_time = null,
    ):Session {
        if (in_array(php_sapi_name(), static::SAPI_CLIS)) {
            $username = DIRECTOY_SEPARATOR === '/' ?
                $_SERVER['user']?? '':
                $_SERVER['username']?? '';

            if (trim($username) === '') {
                throw new RuntimeException(
                    "faild get user name",
                );
            }

            session_id($username);
        }

        return new Session(
            $namespace,
            $data,
            $max_life_time,
        );
    }
}
