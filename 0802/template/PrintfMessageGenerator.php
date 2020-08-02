<?php

/**
*   メッセージテンプレート(printfタイプ)
*
*   @ver 180614
**/

namespace Concerto\template;

use Concerto\template\AbstractMessageGenerator;

class PrintfMessageGenerator extends AbstractMessageGenerator
{
    /**
    *   {inherit}
    *
    **/
    public function generate(array $parameters = []): string
    {
        return vsprintf($this->template, $parameters);
    }
}
