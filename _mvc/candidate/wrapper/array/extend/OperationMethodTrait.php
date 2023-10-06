<?php

/**
*   OperationMethodTrait
*
*   @version 210727
*/

declare(strict_types=1);

namespace candidate\wrapper\array\extend;

trait OperationMethodTrait
{
    /**
    *  spliceAssoc
    *
    *   @param int $position
    *   @param mixed $value,
    *   @return static
    */
    public function spliceAssoc(
        int $offset,
        ?int $length = null,
        mixed $replacement = []
    ): mixed {
        $input = $this->toArray();
        $tail = array_splice($input, $offset);
        $extracted = array_splice($tail, 0, $length);
        $input += ($replacement ?? []) + $tail;

        return new static(
            $input,
            $extracted,
        );
    }

    /**
    *  insert
    *
    *   @param int $offset
    *   @param mixed $values
    *   @param ?bool $preserve_key
    *   @return static
    */
    public function insert(
        int $offset,
        mixed $values,
        ?bool $preserve_key = false,
    ): mixed {
        if ($preserve_key) {
            $spliced_object = static::spliceAssoc(
                $offset,
                0,
                $values,
            );

            return new static(
                $spliced_object->toArray(),
            );
        }

        $array = $this->toArray();
        array_splice(
            $array,
            $offset,
            0,
            $values,
        );
        return new static($array);
    }

    /**
    *   delete
    *
    *   @param int|string|array $keys
    *   @return static
    */
    public function delete(
        int | string | array $keys
    ): static {
        $array = $this->toArray();
        $targets = is_array($keys) ? $keys : [$keys];

        foreach ($targets as $key) {
            unset($array[$key]);
        }
        return new static($array);
    }
}
