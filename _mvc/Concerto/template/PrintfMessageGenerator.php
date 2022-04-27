<?php

/**
*   メッセージテンプレート(printfタイプ)
*
*   @version 180614
*/

declare(strict_types=1);

namespace Concerto\template;

use Concerto\template\AbstractMessageGenerator;

class PrintfMessageGenerator extends AbstractMessageGenerator
{
    /**
    *   {inherit}
    *
    */
    public function generate(array $parameters = []): string
    {
        return vsprintf($this->template, $parameters);
    }
}
