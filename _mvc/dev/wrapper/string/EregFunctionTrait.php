<?php

/**
*   EregFunctionTrait
*
*   @version 220514
*/

declare(strict_types=1);

namespace Concerto\wrapper\string;

use RuntimeException;
use Concerto\wrapper\string\MbRegExOption;

trait EregFunctionTrait
{
    /**
    *   match
    *
    *   @param string $pattern
    *   @param array|string|null $options
    *   @return bool
    */
    public function match(
        string $pattern,
        array|string|null $options = 'pr',
    ): bool {
        $before_encoding = mb_regex_encoding();
        $this->regexEncoding($this->encoding);

        $resolved_options = is_array($options) ?
            MbRegExOption::optionString($options) :
            $options;

        $result = mb_ereg_match(
            $pattern,
            $this->string,
            $options,
        );

        $this->regexEncoding($before_encoding);
        return $result;
    }

    /**
    *   replace
    *
    *   @param string $pattern
    *   @param string $replacement
    *   @param array|string|null $options
    *   @return bool
    */
    public function replace(
        string $pattern,
        string $replacement,
        array|string|null $options = 'pr',
    ): bool {
        $before_encoding = mb_regex_encoding();
        $this->regexEncoding($this->encoding);

        $resolved_options = is_array($options) ?
            MbRegExOption::optionString($options) :
            $options;

        $result = mb_ereg_replace(
            $pattern,
            $this->string,
            $resolved_options,
        );

        if (
            $result === false ||
            $result === null
        ) {
            throw new RuntimeException(
                "replace failure",
            );
        }

        $this->regexEncoding($before_encoding);
        return $result;
    }

    /**
    *   regexEncoding
    *
    *   @param string $encoding
    *   @return void
    */
    protected function regexEncoding(
        string $encoding,
    ): void {
        $result = mb_regex_encoding(
            $encoding,
        );

        if ($result === false) {
            throw new RuntimeException(
                "failure encode:{$encode}",
            );
        }
    }
}
