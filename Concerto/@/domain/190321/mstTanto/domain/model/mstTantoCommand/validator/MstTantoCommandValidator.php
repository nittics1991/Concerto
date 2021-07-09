<?php

use Validate;
use SyainName;

class MstTantoCommandValidator
{
    public static function syainNo(SyainNo $syainNo): bool
    {
        return mb_ereg_match('\A[0-9]{5}ITC\z', $syainNo());
    }

    public static function syainName(SyainName $syainName): bool
    {
        return mb_ereg_match('\A{}\z', $syainName());
    }
}
