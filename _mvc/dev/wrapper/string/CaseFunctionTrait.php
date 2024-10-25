<?php

/**
*   CaseFunctionTrait
*
*   @version 220507
*/

declare(strict_types=1);

namespace Concerto\wrapper\string;

trait CaseFunctionTrait
{
    /**
    *   lower
    *
    *   @return self
    */
    public function lower(): self
    {
        return self::create(
            mb_convert_case(
                $this->string,
                MB_CASE_LOWER,
                $this->encoding,
            ),
            $this->encoding,
        );
    }

    /**
    *   title
    *
    *   @return self
    */
    public function title(): self
    {
        return self::create(
            mb_convert_case(
                $this->string,
                MB_CASE_TITLE,
                $this->encoding,
            ),
            $this->encoding,
        );
    }

    /**
    *   upper
    *
    *   @return self
    */
    public function upper(): self
    {
        return self::create(
            mb_convert_case(
                $this->string,
                MB_CASE_UPPER,
                $this->encoding,
            ),
            $this->encoding,
        );
    }
}
