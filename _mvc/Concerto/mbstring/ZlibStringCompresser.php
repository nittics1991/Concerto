<?php

/**
*   ZlibStringCompresser
*
*   @version 220322
*/

declare(strict_types=1);

namespace Concerto\mbstring;

use RuntimeException;
use Throwable;
use Concerto\mbstring\StringCompressorInterface;

class ZlibStringCompresser implements StringCompressorInterface
{
    /**
    *   @var int encoging
    */
    public const GZIP = ZLIB_ENCODING_GZIP;
    public const DEFLATE = ZLIB_ENCODING_DEFLATE;
    public const RAW = ZLIB_ENCODING_RAW;

    /**
    *   @var int
    */
    protected int $encoding;

    /**
    *   @var int
    */
    protected int $level;

    /**
    *   __construct
    *
    *   @param int $encoding
    *   @param int $level
    */
    public function __construct(
        int $encoding = self::GZIP,
        int $level = -1,
    ) {
        $this->encoding = $encoding;
        $this->level = $level;
    }

    /**
    *   {inherit}
    *
    */
    public function compress(string $string): string
    {
        $compressed = zlib_encode(
            $string,
            $this->encoding,
            $this->level,
        );

        if ($compressed === false) {
            throw new RuntimeException(
                "Could not compress the string"
            );
        }
        return $compressed;
    }

    /**
    *   {inherit}
    *
    */
    public function expand(string $string): string
    {
        $expanded = zlib_decode(
            $string,
        );

        if ($expanded === false) {
            throw new RuntimeException(
                "Could not compress the string"
            );
        }
        return $expanded;
    }

    /**
    *   {inherit}
    *
    */
    public function isCompressed(string $string): bool
    {
        try {
            return $this->expand($string) !== $string;
        } catch (Throwable $e) {
            return false;
        }
    }
}
