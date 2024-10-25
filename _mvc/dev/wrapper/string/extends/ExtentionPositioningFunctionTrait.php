<?php

/**
*   ExtentionPositioningFunctionTrait
*
*   @version 220507
*/

declare(strict_types=1);

namespace Concerto\wrapper\string\extends;

trait ExtentionPositioningFunctionTrait
{
    /**
    *   position
    *
    *   @param string $needle
    *   @param int $offset
    *   @param bool $from_behind
    *   @param bool $ingore
    *   @return int
    */
    public function position(
        string $needle,
        int $offset = 0,
        bool $from_behind = false,
        bool $ingore = false,
    ): int {
        $direction = $from_behind ? 'r' : '';
        $case = $ingore ? '' : 'i';

        $callback = "mb_str{$case}{$direction}pos";

        return $this->resolvePosition(
            $callback,
            $needle,
            $offset,
        );
    }
}
