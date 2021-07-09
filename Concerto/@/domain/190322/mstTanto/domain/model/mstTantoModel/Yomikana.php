<?php

use ValueObject;

class Yomikana extends ValueObject
{
    public static function createFromAlphabet(string $name): Yomikana
    {
        $name = MbString::kana($name);
        return new static($name);
    }
}
