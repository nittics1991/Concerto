<?php

/**
*   メッセージテンプレート
*
*   @ver 180614
**/

namespace Concerto\template;

interface MessageGeneratorInterface
{
    /**
    *   生成
    *
    *   @param array $parameters
    *   @return string
    **/
    public function generate(array $parameters): string;
}
