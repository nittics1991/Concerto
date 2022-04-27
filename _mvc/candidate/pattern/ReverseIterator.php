<?php

/**
*   ReverseIterator
*
*   @version 160805
*/

declare(strict_types=1);

namespace candidate\pattern;

use ArrayIterator;
use Traversable;

class ReverseIterator extends ArrayIterator
{
    /**
    *   __construct
    *
    *   @param mixed $iterator
    */
    public function __construct($iterator)
    {
        $target = ($iterator instanceof Traversable) ?
            iterator_to_array($iterator) : $iterator;
        parent::__construct(array_reverse($target));
    }
}
