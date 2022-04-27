<?php

/**
*   メッセージテンプレート
*
*   @version 180614
*/

declare(strict_types=1);

namespace Concerto\template;

interface MessageGeneratorInterface
{
    /**
    *   生成
    *
    *   @param mixed[] $parameters
    *   @return string
    */
    public function generate(array $parameters): string;
}
