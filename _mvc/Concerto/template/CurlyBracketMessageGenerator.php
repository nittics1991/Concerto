<?php

/**
*   メッセージテンプレート({{xxx}}テンプレートタイプ)
*
*   @version 201013
*   @exapmle $template = 'string to be replaced by {{keyword}}'
*           $values={'keyword' => $value}
*/

declare(strict_types=1);

namespace Concerto\template;

use Concerto\template\AbstractMessageGenerator;

class CurlyBracketMessageGenerator extends AbstractMessageGenerator
{
    /**
    *   {inherit}
    *
    */
    public function generate(array $parameters = []): string
    {
        $template = $this->template;

        foreach ($parameters as $key => $val) {
            $template = (string)mb_ereg_replace(
                '\{\{' . $key . '\}\}',
                (string)$val,
                (string)$template
            );
        }
        return $template;
    }
}
