<?php

/**
*   TranslatorInterface
*
*   @ver 180612
*/

declare(strict_types=1);

namespace candidate\translation;

interface TranslatorInterface
{
    /**
    *   変換
    *
    *   @param string $id
    *   @param mixed[] $params
    *   @return string
    */
    public function trans(string $id, array $params): string;
}
