<?php

/**
*   Post
*
*   @version 240826
*/

declare(strict_types=1);

namespace Concerto\standard;

use Concerto\standard\DataContainerValidatable;

/**
*   @template TValue
*   @extends DataContainerValidatable<TValue>
*/
class Post extends DataContainerValidatable
{
    /**
    *   __construct
    *
    *   @param null|string[] $data
    */
    public function __construct(
        ?array $data = null
    ) {
        $this->data = $data ?? $_POST;
    }

    /**
    *   @inheritDoc
    */
    protected function validCom(
        string|int $key,
        mixed $val
    ): bool {
        $result = true;

        if (is_array($val)) {
            foreach ($val as $key2 => $val2) {
                $result = $this->validCom($key2, $val2) &&
                    $result;
            }
        } else {
            if (!mb_check_encoding(strval($val))) {
                $this->valid[$key][] = 'invalid encoding';
                $result = false;
            }

            //mb_ereg_matchではエラーの場合がある(php8.0.3)
            if (
                !preg_match(
                    '/\A[\x20-\x7e\x80-\xff\x09-\x0a\x0d]*\z/',
                    strval($val)
                )
            ) {
                $this->valid[$key][] = 'invalid code';
                $result = false;
            }
        }
        return $result;
    }

    /**
    *   isAjax
    *
    *   @return bool
    */
    protected function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) ===
            'xmlhttprequest';
    }

    /**
    *   @inheritDoc
    *
    */
    public function offsetGet(
        mixed $offset
    ): mixed {
        $method = 'get' .
            mb_convert_case(
                strval($offset),
                MB_CASE_TITLE
            );

        if (method_exists($this, $method)) {
            $value = $this->$method($offset);
        } else {
            $value = parent::offsetGet($offset);
        }
        return $this->getFilterCom($value);
    }

    /**
    *   get共通フィルタ
    *
    *   @param mixed $val
    *   @return mixed
    *   @caution NULLは''になる
    */
    protected function getFilterCom(
        mixed $val
    ): mixed {
        if (is_array($val)) {
            return array_map(
                function ($val2) {
                    return $this->getFilterCom($val2);
                },
                $val
            );
        }
        return $this->doGetFilterCom(strval($val));
    }

    /**
    *   get共通フィルタ実行
    *
    *   @param string $val
    *   @return string
    */
    protected function doGetFilterCom(
        string $val
    ): string {
        return strip_tags($val);
    }

    /**
    *   getフィルタ
    *
    *   @see 必要に応じてフィルタ処理を作成する
    */
    //protected function getXxxx($key)
}
