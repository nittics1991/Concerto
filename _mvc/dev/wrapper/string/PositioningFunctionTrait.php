<?php

/**
*   PositioningFunctionTrait
*
*   @version 220507
*/

declare(strict_types=1);

namespace Concerto\wrapper\string;

use RuntimeException;

trait PositioningFunctionTrait
{
    /**
    *   ipos
    *
    *   @param string $needle
    *   @param int $offset
    *   @return int
    */
    public function ipos(
        string $needle,
        int $offset = 0,
    ): int {
        return $this->resolvePosition(
            'mb_stripos',
            $needle,
            $offset,
        );
    }

    /**
    *   irpos
    *
    *   @param string $needle
    *   @param int $offset
    *   @return int
    */
    public function irpos(
        string $needle,
        int $offset = 0,
    ): int {
        return $this->resolvePosition(
            'mb_strirpos',
            $needle,
            $offset,
        );
    }

    /**
    *   pos
    *
    *   @param string $needle
    *   @param int $offset
    *   @return int
    */
    public function pos(
        string $needle,
        int $offset = 0,
    ): int {
        return $this->resolvePosition(
            'mb_strpos',
            $needle,
            $offset,
        );
    }

    /**
    *   rpos
    *
    *   @param string $needle
    *   @param int $offset
    *   @return int
    */
    public function rpos(
        string $needle,
        int $offset = 0,
    ): int {
        return $this->resolvePosition(
            'mb_strrpos',
            $needle,
            $offset,
        );
    }

    /**
    *   resolvePosition
    *
    *   @param callable $callback
    *   @param string $needle
    *   @param int $offset
    *   @return int
    */
    protected function resolvePosition(
        callable $callback,
        string $needle,
        int $offset = 0,
    ): int {
        $position = $callback(
            $this->string,
            $needle,
            $offset,
            $this->encoding,
        );

        if ($position === false) {
            throw new RuntimeException(
                "can not take it out",
            );
        }

        return $position;
    }
}
