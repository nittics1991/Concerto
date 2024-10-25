<?php

/**
*   ConvertFunctionTrait
*
*   @version 220507
*/

declare(strict_types=1);

namespace Concerto\wrapper\string;

use InvalidArgumentException;
use RuntimeException;
use Concerto\wrapper\string\MbKanaMode;

trait ConvertFunctionTrait
{
    /**
    *   encode
    *
    *   @param string $encoding
    *   @return self
    */
    public function encode(
        string $encoding,
    ): self {
        if (!$this->validEncodeName($encoding)) {
            throw new InvalidArgumentException(
                "not defined encoding:{$encoding}",
            );
        }

        $encoded = mb_convert_encoding(
            $this->string,
            $encoding,
            $this->encoding,
        );

        return self::create(
            $encoded,
            $encoding,
        );
    }

    /**
    *   kana
    *
    *   @param string[]|string $mode
    *   @return self
    */
    public function kana(
        array|string $mode,
    ): self {
        $resolved = is_array($mode) ?
            MbKanaMode::modeString($mode) :
            $mode;

        return self::create(
            mb_convert_kana(
                $this->string,
                $resolved,
                $this->encoding,
            ),
            $this->encoding,
        );
    }
}
