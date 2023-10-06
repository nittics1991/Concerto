<?php

/**
*   RespectException
*
*   @ver 180621
*/

declare(strict_types=1);

namespace dev\validation\respect\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class HanKatakanaException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} must contain only katakana',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} must not contain katakana',
        ],
    ];
}
