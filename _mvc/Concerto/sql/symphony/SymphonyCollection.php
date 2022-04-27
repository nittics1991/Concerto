<?php

/**
*   SymphonyCollection
*
*   @version 210614
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
    *   @var array
    */
    private array $dataset;

    /**
    *   __construct
    *
    *   @param array $assocData
    */
    public function __construct(array $assocData)
    {
        $this->dataset = $this->convert($assocData);
    }

    /**
    *   convert
    *
    *   @param array $assocData
    *   @return array
    */
    public function convert(array $assocData)
    {
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
    *   {inherit}
    *
    */
    public function getIterator(): Traversable
    {
        return new ArrayObject($this->dataset);
    }

    /**
    *   toArray
    *
    *   @return array
    */
    public function toArray(): array
    {
        return $this->dataset;
    }
}
