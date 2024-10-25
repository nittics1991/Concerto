<?php

/**
*   TakeFunctionTrait
*
*   @version 220507
*/

declare(strict_types=1);

namespace Concerto\wrapper\string;

use RuntimeException;

trait TakeFunctionTrait
{
    /**
    *   ichr
    *
    *   @param string $needle
    *   @param bool $before_needle
    *   @return string
    */
    public function ichr(
        string $needle,
        bool $before_needle = false,
    ): string {
        return $this->resolveTakeOut(
            'mb_strichr',
            $needle,
            $before_needle,
        );
    }

    /**
    *   istr
    *
    *   @param string $needle
    *   @param bool $before_needle
    *   @return string
    */
    public function istr(
        string $needle,
        bool $before_needle = false,
    ): string {
        return $this->resolveTakeOut(
            'mb_stristr',
            $needle,
            $before_needle,
        );
    }

    /**
    *   rchr
    *
    *   @param string $needle
    *   @param bool $before_needle
    *   @return string
    */
    public function rchr(
        string $needle,
        bool $before_needle = false,
    ): string {
        return $this->resolveTakeOut(
            'mb_strrchr',
            $needle,
            $before_needle,
        );
    }

    /**
    *   str
    *
    *   @param string $needle
    *   @param bool $before_needle
    *   @return string
    */
    public function str(
        string $needle,
        bool $before_needle = false,
    ): string {
        return $this->resolveTakeOut(
            'mb_strstr',
            $needle,
            $before_needle,
        );
    }

    /**
    *   resolveTakeOut
    *
    *   @param callable $callback
    *   @param string $needle
    *   @param bool $before_needle
    *   @return string
    */
    protected function resolveTakeOut(
        callable $callback,
        string $needle,
        bool $before_needle = false,
    ): string {
        $taked = $callback(
            $this->string,
            $needle,
            $before_needle,
            $this->encoding,
        );

        if ($taked === false) {
            throw new RuntimeException(
                "can not take it out",
            );
        }

        return $taked;
    }

    /**
    *   substr
    *
    *   @param int $start
    *   @param ?int $length
    *   @return string
    */
    public function substr(
        int $start,
        ?int $length = null,
    ): string {
        return mb_substr(
            $this->string,
            $start,
            $length,
            $this->encoding,
        );
    }

    /**
    *   split
    *
    *   @param string $pattern
    *   @param ?int $limit
    *   @return array
    */
    public function split(
        string $pattern,
        int $limit = -1
    ): array {
        $splited = mb_split(
            $pattern,
            $this->string,
            $limit,
            $this->encoding,
        );

        if ($splited === false) {
            throw new RuntimeException(
                "failure split",
            );
        }

        return $splited;
    }

    /**
    *   str_split
    *
    *   @param int $length
    *   @return array
    */
    public function str_split(
        int $length = 1
    ): array {
        return mb_split(
            $this->string,
            $length,
            $this->encoding,
        );
    }
}
