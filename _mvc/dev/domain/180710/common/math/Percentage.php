<?php

/**
*   Percentage
*
*   @version 181029
*/

namespace dev\percentage;

use InvalidArgumentException;

class Percentage
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
    *   入力型
    *
    *   @var string
    */
    const INT = 'int';
    const FLOAT = 'float';

    /**
    *   amount
    *
    *   @var string
    */
    protected $amount;

    /**
    *   dataType
    *
    *   @var string
    */
    protected $dataType;

    /**
    *   __construct
    *
    *   @param int|float|string
    */
    public function __construct($amount, $type = Percentage::INT)
    {
        $this->validateDataType($type);
        $this->dataType = $type;
        $this->amount = strval($amount);
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
    *   add(複数)
    *
    *   @param ...Percentage
    *   @return Percentage
    */
    public function add(Percentage ...$operands): Percentage
    {
        $result = $this->amount;

        foreach ($operands as $val) {
            $result += $val;
        }
        return new static($result);
    }

    /**
    *   sub(複数)
    *
    *   @param ...Percentage
    *   @return Percentage
    */
    public function sub(Percentage ...$operands): Percentage
    {
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
    *   @return Percentage
    */
    public function mul($operand): Percentage
    {
        $result = $this->amount * strval($operand);
        return new static($result);
    }

    /**
    *   div
    *
    *   @param int|float|string
    *   @param string
    *   @return Percentage
    */
    public function div($operand, $roundingMode = Percentage::FLOOR): Percentage
    {
        $result = $this->amount / strval($operand);
        $result = $this->round($result, $roundingMode);
        return new static($result);
    }

    /**
    *   mod
    *
    *   @param int|float|string
    *   @return Percentage
    */
    public function mod($operand): Percentage
    {
        $result = $this->amount % strval($operand);
        return new static($result);
    }

    /**
    *   小数点以下処理
    *
    *   @param int|float
    *   @return Percentage
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
            case Percentage::CEIL:
            case Percentage::FLOOR:
            case Percentage::ROUND:
                return;
        }
        throw new InvalidArgumentException("not defined rounding mode");
    }

    /**
    *   データ型検証
    *
    *   @param string
    *   @throws InvalidArgumentException
    */
    protected function validateDataType($roundingMode)
    {
        switch ($roundingMode) {
            case Percentage::INT:
            case Percentage::FLOAT:
                return;
        }
        throw new InvalidArgumentException("not defined data type");
    }

    /**
    *   getDataType
    *
    *   @return string
    */
    public function getDataType(): string
    {
        return $this->dataType;
    }

    /**
    *   データ型比較
    *
    *   @param Percentage
    *   @return bool
    */
    public function sameType(Percentage $percentage): bool
    {
        return $this->dataType === $percentage->getDataType();
    }

    /**
    *   toFloat
    *
    *   @return float
    */
    public function toFloat(): float
    {
        return floatval($this->amount);
    }

    /**
    *   toInt
    *
    *   @return int
    */
    public function toInt(): int
    {
        return intval($this->amount);
    }

    /**
    *   equals
    *
    *   @param Percentage
    *   @return bool
    */
    public function equals(Percentage $percentage): bool
    {
        return $this->amount === $percentage->getAmount();
    }

    /**
    *   greaterThan
    *
    *   @param Percentage
    *   @return bool
    */
    public function greaterThan(Percentage $percentage): bool
    {
        return $this->amount >= $percentage->getAmount();
    }

    /**
    *   greater
    *
    *   @param Percentage
    *   @return bool
    */
    public function greater(Percentage $percentage): bool
    {
        return $this->amount > $percentage->getAmount();
    }

    /**
    *   lessThan
    *
    *   @param Percentage
    *   @return bool
    */
    public function lessThan(Percentage $percentage): bool
    {
        return $this->amount <= $percentage->getAmount();
    }

    /**
    *   less
    *
    *   @param Percentage
    *   @return bool
    */
    public function less(Percentage $percentage): bool
    {
        return $this->amount < $percentage->getAmount();
    }

    /**
    *   isZero
    *
    *   @param Percentage
    *   @return bool
    */
    public function isZero(): bool
    {
        return $this->amount === 0;
    }

    /**
    *   isFull
    *
    *   @param Percentage
    *   @return bool
    */
    public function isFull(): bool
    {
        return $this->amount === 100;
    }
}
