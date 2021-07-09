<?php

/**
*   Invokable
*
*   @version 190523
*/

declare(strict_types=1);

namespace Concerto\standard;

interface Invokable
{
    /**
    *     処理
    *
    *   @param mixed ...$argv
    *   @return mixed
    */
    public function __invoke(...$argv);
}
