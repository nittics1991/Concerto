<?php

/**
*   SymphonyCollection
*
*   @version 230117
*/

declare(strict_types=1);

namespace Concerto\sql\symphony;

use ArrayObject;
use IteratorAggregate;
use Traversable;

class SymphonyCollection implements IteratorAggregate
{
    /**
    *   dataset
    *
    *   @var mixed[]
    */
    private array $dataset;

    /**
    *   __construct
    *
    *   @param string[][] $assocData
    */
    public function __construct(
        array $assocData
    ) {
        $this->dataset = $this->convert($assocData);
    }

    /**
    *   convert
    *
    *   @param string[][] $assocData
    *   @return mixed[]
    */
    public function convert(
        array $assocData
    ): array {
        mb_convert_variables(
            'UTF-8',
            'SJIS',
            $assocData
        );

        return array_map(
            function ($list) {
                return array_change_key_case(
                    $list,
                    CASE_LOWER
                );
            },
            $assocData
        );
    }

    /**
    *   @inheritDoc
    *
    */
    public function getIterator(): Traversable
    {
        return new ArrayObject($this->dataset);
    }

    /**
    *   toArray
    *
    *   @return mixed[]
    */
    public function toArray(): array
    {
        return $this->dataset;
    }
}
