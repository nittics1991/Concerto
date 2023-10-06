<?php

/**
*   メッセージテンプレート(printfタイプ)
*
*   @version 221207
*/

declare(strict_types=1);

namespace Concerto\template;

use InvalidArgumentException;
use Concerto\template\AbstractMessageGenerator;

class PrintfMessageGenerator extends AbstractMessageGenerator
{
    /**
    *   @inheritDoc
    *
    */
    public function generate(
        array $parameters = []
    ): string {
        foreach ($parameters as $key => $val) {
            if (
                !is_scalar($val) &&
                !is_null($val)
            ) {
                throw new InvalidArgumentException(
                    "must be scalar|null:{$key}",
                );
            }
        }

        return vsprintf($this->template, $parameters);
    }
}
