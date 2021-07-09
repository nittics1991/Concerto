<?php

use ValueObject;

class SyainNo extends ValueObject implements ValueValidateInterface
{
    public static function valid($target): bool
    {
        return is_string($target) &&
            mb_ereg_match('\A[0-9]{5}ITC\z', $target);
    }
}
