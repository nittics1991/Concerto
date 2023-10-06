<?php

/**
*   RecordsetTestTrait
*
*   @version 230404
*/

declare(strict_types=1);

namespace test\Concerto;

trait RecordsetTestTrait
{
    /**
    *   itaratorsToTable
    *
    *   @param iterable $iterators
    *   @return mixed[]
    */
    public function itaratorsToTable(
        iterable $iterators,
    ): array {
        $table = [];

        foreach ($iterators as $list) {
            $table[] = iterator_to_array($list);
        }

        return $table;
    }

    /**
    *   recordsetCount
    *
    *   @param iterable $iterators
    *   @return int
    */
    public function recordsetCount(
        iterable $iterators,
    ): int {
        if (is_array($iterators)) {
            return count($iterators);
        }

        $copied = clone $iterators;

        return iterator_count($copied);
    }

    /**
    *   recordsetHeader
    *
    *   @param iterable $iterators
    *   @return int[]|string[]
    */
    public function recordsetHeader(
        iterable $iterators,
    ): array {
        foreach ($iterators as $list) {
            return array_keys(
                iterator_to_array($list),
            );
        }
    }
}
