<?php

/**
*   ExtentionEregFunctionTrait
*
*   @version 220507
*/

declare(strict_types=1);

namespace Concerto\wrapper\string;

use RuntimeException;
use Concerto\wrapper\string\MbRegExOption;

trait ExtentionEregFunctionTrait
{
    /**
    *   matchAll
    *
    *   @param string $pattern
    *   @param array|string|null $options
    *   @return bool
    */
    public function match(
        string $pattern,
        array|string|null $options = 'pr',
    ): array {
        $before_encoding = mb_regex_encoding();

        $resolved_options = is_array($options) ?
            MbRegExOption::optionString($options) :
            $options;

        mb_regex_set_options($resolved_options);

        $matches = [];

        $result = mb_ereg(
            $pattern,
            $this->string,
            $matches,
        );

        $this->regexEncoding($before_encoding);
        return $matches;
    }
}
