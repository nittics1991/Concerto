<?php

/**
*   LogWriterInterface
*
*   @version 230927
*/

declare(strict_types=1);

namespace Concerto\log;

interface LogWriterInterface
{
    /**
    *   setFormat
    *
    *   @param string $format
    *   @return mixed
    */
    public function setFormat(
        string $format
    ): mixed;

    /**
    *   write
    *
    *   @param mixed $messages
    *   @return void
    */
    public function write(
        mixed $messages
    ): void;
}
