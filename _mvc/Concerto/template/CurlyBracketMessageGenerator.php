<?php

/**
*   メッセージテンプレート({{xxx}}テンプレートタイプ)
*
*   @version 230927
*   @exapmle $template = 'string to be replaced by {{keyword}}'
*           $values={'keyword' => $value}
*/

declare(strict_types=1);

namespace Concerto\template;

use InvalidArgumentException;
use Concerto\template\AbstractMessageGenerator;

class CurlyBracketMessageGenerator extends AbstractMessageGenerator
{
    /**
    *   @inheritDoc
    *
    */
    public function generate(
        array $parameters = []
    ): string {
        $template = $this->template;

        foreach ($parameters as $key => $val) {
            if (
                !is_scalar($val) &&
                !is_null($val)
            ) {
                throw new InvalidArgumentException(
                    "must be scalar|null:{$key}",
                );
            }

            $template = (string)mb_ereg_replace(
                '\{\{' . $key . '\}\}',
                strval($val),
                strval($template),
            );
        }

        return $template;
    }
}
