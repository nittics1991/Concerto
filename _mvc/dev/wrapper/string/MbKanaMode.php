<?php

/**
*   MbKanaMode
*
*   @version 220507
*/

declare(strict_types=1);

namespace Concerto\wrapper\string;

final class MbKanaMode
{
    /**
    *   @var string
    */
    public const ZEN_ALPHA_HAN = 'r';
    public const HAN_ALPHA_ZEN = 'R';
    public const ZEN_DIGIT_HAN = 'n';
    public const HAN_DIGIT_ZEN = 'N';
    public const ZEN_ALNUM_HAN = 'a';
    public const HAN_ALNUM_ZEN = 'A';
    public const ZEN_SPACE_HAN = 's';
    public const HAN_SPACE_ZEN = 'S';
    public const ZEN_KANA_HAN = 'k';
    public const HAN_KANA_ZEN = 'c';
    public const ZEN_YOMI_HAN = 'C';
    public const HAN_YOMI_ZEN = 'K';
    public const DAKUTEN = 'V';

    /**
    *   modeString
    *
    *   @param string[] $modes
    *   @return string
    */
    public static function modeString(
        array $modes,
    ): string {
        return implode('', $modes);
    }
}
