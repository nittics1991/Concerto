<?php

use ValueObject;

class TouituUserId extends ValueObject implements ValueValidateInterface
{
    public static function valid($target): bool
    {
        return is_string($target) &&
            mb_ereg_match('\A[a-z]([0-9]{5}[a-z]{2}|[0-9]{4}[a-z]{3})\z', $target);
    }
}
