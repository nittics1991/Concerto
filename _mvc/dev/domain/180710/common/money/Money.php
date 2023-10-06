<?php

/**
*   Money
*
*   @version 181029
*/

namespace dev\money;

use InvalidArgumentException;
use RuntimeException;

class Money
{
    /**
    *   小数点以下処理
    *
    *   @var string
    */
    const CEIL = 'ceil';
    const FLOOR = 'floor';
    const ROUND = 'round';

    /**
    *   amount
    *
    *   @var string
    */
    protected $amount;

    /**
    *   currency
    *
    *   @var Currency
    */
    protected $currency;

    /**
    *   __construct
    *
    *   @param int|float|string
    *   @param Currency|null
    */
    public function __construct($amount, $currency = null)
    {
        $this->amount = strval($amount);
        $this->currency = $currency;
    }

    /**
    *   getAmount
    *
    *   @return string
    */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
    *   getCUrrency
    *
    *   @return Currency
    */
    public function getCUrrency(): Currency
    {
        return $this->currency;
    }

    /**
    *   isSameCurrency
    *
    *   @param Money
    *   @return bool
    *   @throws RuntimeException,InvalidArgumentException
    */
    public function isSameCUrrency(Money $other): bool
    {
        if (is_null($this->currency)) {
            throw new RuntimeException("this money deoes not define currency");
        }

        if (is_null($other->getCurrency())) {
            throw new InvalidArgumentException("target money does not define currency");
        }
        return $this->currency->equals($other->getCurrency());
    }

    /**
    *   assertSameCurrency
    *
    *   @param Money
    *   @throws RuntimeException,InvalidArgumentException
    */
    protected function assertSameCurrency(Money $other)
    {
        if (is_null($this->isSameCUrrency($other))) {
            throw new RuntimeException("currency does not match");
        }
    }

    /**
    *   assertSameCurrencys
    *
    *   @param Money
    *   @throws RuntimeException,InvalidArgumentException
    */
    protected function assertSameCurrencies(array $others)
    {
        foreach ($others as $other) {
            $this->isSameCUrrency($other);
        }
    }

    /**
    *   add(複数)
    *
    *   @param ...Money
    *   @return Money
    */
    public function add(Money ...$operands): Money
    {
        $this->assertSameCurrencies($operands);
        $result = $this->amount;

        foreach ($operands as $val) {
            $result += $val;
        }
        return new static($result);
    }

    /**
    *   sub(複数)
    *
    *   @param ...Money
    *   @return Money
    */
    public function sub(Money ...$operands): Money
    {
        $this->assertSameCurrencies($operands);
        $result = $this->amount;

        foreach ($operands as $val) {
            $result -= $val;
        }
        return new static($result);
    }

    /**
    *   mul
    *
    *   @param int|float|string
    *   @return Money
    */
    public function mul($operand): Money
    {
        $result = $this->amount * strval($operand);
        return new static($result);
    }

    /**
    *   div
    *
    *   @param int|float|string
    *   @param string
    *   @return Money
    */
    public function div($operand, $roundingMode = Money::FLOOR): Money
    {
        $result = $this->amount / strval($operand);
        $result = $this->round($result, $roundingMode);
        return new static($result);
    }

    /**
    *   mod
    *
    *   @param int|float|string
    *   @return Money
    */
    public function mod($operand): Money
    {
        $result = $this->amount % strval($operand);
        return new static($result);
    }

    /**
    *   小数点以下処理
    *
    *   @param int|float
    *   @return Money
    */
    protected function round($val, $roundingMode)
    {
        $this->validateRoundingMode($roundingMode);
        return call_user_func($roundingMode, $val);
    }

    /**
    *   小数点以下処理モード検証
    *
    *   @param string
    *   @throws InvalidArgumentException
    */
    protected function validateRoundingMode($roundingMode)
    {
        switch ($roundingMode) {
            case Money::CEIL:
            case Money::FLOOR:
            case Money::ROUND:
                return;
        }
        throw new InvalidArgumentException("not defined rounding mode");
    }

    /**
    *   equals
    *
    *   @param Money
    *   @return bool
    */
    public function equals(Money $other): bool
    {
        $this->assertSameCurrency($other);
        return $this->amount === $other->getAmount();
    }

    /**
    *   greaterThan
    *
    *   @param Money
    *   @return bool
    */
    public function greaterThan(Money $other): bool
    {
        $this->assertSameCurrency($other);
        return $this->amount >= $other->getAmount();
    }

    /**
    *   greater
    *
    *   @param Money
    *   @return bool
    */
    public function greater(Money $other): bool
    {
        $this->assertSameCurrency($other);
        return $this->amount > $other->getAmount();
    }

    /**
    *   lessThan
    *
    *   @param Money
    *   @return bool
    */
    public function lessThan(Money $other): bool
    {
        $this->assertSameCurrency($other);
        return $this->amount <= $other->getAmount();
    }

    /**
    *   less
    *
    *   @param Money
    *   @return bool
    */
    public function less(Money $other): bool
    {
        $this->assertSameCurrency($other);
        return $this->amount < $other->getAmount();
    }

    /**
    *   isZero
    *
    *   @param Money
    *   @return bool
    */
    public function isZero(): bool
    {
        return $this->amount === 0;
    }

    /**
    *   isPositive
    *
    *   @param Money
    *   @return bool
    */
    public function isPositive(): bool
    {
        return $this->amount > 0;
    }

    /**
    *   isNegative
    *
    *   @param Money
    *   @return bool
    */
    public function isNegative(): bool
    {
        return $this->amount < 0;
    }
}
