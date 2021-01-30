<?php

/**
*   BacktraceCollection
*
*   @version 200405
*/

declare(type_stricts=1);

namespace Concerto\trace;

use Countable;
use IteratorAggregate;

class BacktraceCollection implements  IteratorAggregate,
    Countable
{
    private array $container;
    
    /**
    *   create
    *
    *   @param array $traces
    *   @return BacktraceCollection
    */
    public static function create(int $limit = 0):BacktraceCollection
    {
        return new static(
            debug_backtrace($limit)
        );
    }
    
    /**
    *   __construct
    *
    *   @param array $traces
    */
    public function __construct(array $traces)
    {
        $this->fromArray($traces);
    }
    
    /**
    *   fromArray
    *
    *   @param array $traces
    */
    private function fromArray(array $traces)
    {
        foreach($traces as $trace) {
            $this->container[] = new Backtrace($trace);
        }
    }
    
    /**
    *   {inherit}
    *
    */
    public function getIterator()
    {
        foreach($this->container as $trace) {
            yield $trace;
        }
    }
    
    /**
    *   {inherit}
    *
    */
    public function count()
    {
        return count($this->container);
    }
    
    /**
    *   toArray
    *
    *   @return array
    */
    public function toArray(): array
    {
        $result = [];
        foreach($this->container as $trace) {
            $result[] = $trace->toArray();
        }
        return $result;
    }
}
