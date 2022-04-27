<?php

/**
*   テンプレート
*
*   @version 170207
*/

declare(strict_types=1);

namespace Concerto\template;

interface TemplateInterface
{
    /**
    *   描画
    *
    *   @param mixed $dataset
    *   @return string
    */
    public function render($dataset): string;
}
