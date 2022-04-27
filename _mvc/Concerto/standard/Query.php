<?php

/**
*   Query
*
*   @version 220203
*/

declare(strict_types=1);

namespace Concerto\standard;

use Concerto\standard\DataContainerValidatable;

class Query extends DataContainerValidatable
{
    /**
    *   __construct
    *
    *   @param ?array $data
    */
    public function __construct(
        ?array $data = null
    ) {
        $this->data = $data ?? $_GET;
    }

    /**
    *   バリデート全変数共通処理
    *
    *   @param string $key 変数名
    *   @param mixed $val データ
    *   @return bool
    */
    protected function validCom($key, $val): bool
    {
        if (!is_array($val)) {
            return $this->doValidCom($key, (string)$val);
        }
        $result = true;

        foreach ($val as $data) {
            $result = $result & $this->doValidCom($key, $data);
        }
        return (bool)$result;
    }

    /**
    *   バリデート全変数共通処理実行
    *
    *   @param string $key 変数名
    *   @param string $val データ
    *   @return bool
    */
    protected function doValidCom(string $key, string $val)
    {
        $result = true;
        if (!mb_check_encoding((string)$val)) {
            $this->valid[$key][] = 'invalid encoding';
            $result = false;
        }

            //mb_ereg_matchではエラーの場合がある(php8.0.3)
        if (!preg_match('/\A[\x20-\x7e\x80-\xff]*\z/', $val)) {
            $this->valid[$key][] = 'invalid code';
            $result = false;
        }
        return $result;
    }

    /**
    *   AJAX判定
    *
    *   @return bool
    */
    protected function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) ===
            'xmlhttprequest';
    }
}
