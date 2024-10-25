<?php

/**
*   Query
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
class Query extends DataContainerValidatable
{
    /**
    *   __construct
    *
    *   @param ?string[] $data
    */
    public function __construct(
        ?array $data = null
    ) {
        $this->data = $data ?? $_GET;
    }

    /**
    *   @inheritDoc
    */
    protected function validCom(
        string|int $key,
        mixed $val
    ): bool {
        if (!is_array($val)) {
            return $this->doValidCom($key, strval($val));
        }
        $result = true;

        foreach ($val as $data) {
            $result = $this->doValidCom($key, $data) &&
                $result;
        }
        return (bool)$result;
    }

    /**
    *   doValidCom
    *
    *   @param string|int $key
    *   @param string $val
    *   @return bool
    */
    protected function doValidCom(
        string|int $key,
        string $val
    ): bool {
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
}
