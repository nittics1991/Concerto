<?php

/**
*   RespectRule
*
*   @ver 180621
**/

declare(strict_types=1);

namespace Concerto\validation\respect\Rules;

use Respect\Validation\Rules\AbstractRule;

class HanKatakana extends AbstractRule
{
    public function validate($input)
    {
        return mb_ereg_match('^[｡-ﾟ]+$', $input);
    }
}
