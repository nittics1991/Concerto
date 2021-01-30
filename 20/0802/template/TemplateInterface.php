<?php

/**
*   テンプレート
*
*   @ver 170207
**/

namespace Concerto\template;

interface TemplateInterface
{
    /**
    *   描画
    *
    *   @param mixed $dataset
    *   @return string
    **/
    public function render($dataset): string;
}
