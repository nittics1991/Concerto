<?php

/**
*   メッセージテンプレート({{xxx}}テンプレートタイプ)
*
*   @ver 180614
*   @exapmle $template = 'string to be replaced by {{keyword}}'
*           $values={'keyword' => $value}
**/

namespace Concerto\template;

use Concerto\template\AbstractMessageGenerator;

class CurlyBracketMessageGenerator extends AbstractMessageGenerator
{
    /**
    *   {inherit}
    *
    **/
    public function generate(array $parameters = []): string
    {
        $template = $this->template;
        
        foreach ($parameters as $key => $val) {
            $template = (string)mb_ereg_replace(
                '\{\{' . $key . '\}\}',
                $val,
                $template
            );
        }
        return $template;
    }
}
