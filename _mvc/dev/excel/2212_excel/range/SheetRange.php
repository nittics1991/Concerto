<?php

namespace Concerto\excel;


class SheetRange
{
    /**
    *   @var array
    */
    private array container = [];
    
    /**
    *   @var int
    */
    private int max_x = 0;
    
    /**
    *   @var int
    */
    private int max_y = 0;
    
    /**
    *   @var array
    */
    private array cache = [];
    
    /**
    *   @var bool
    */
    private bool $dirty = false;

    /**
    *   __construct
    * 
    *   @param array $data
    *   @param int $x
    *   @param int $y
    */
    public function __construct(
        array $data,
        int $x = 0,
        int $y = 0,
    ) {
        $this->add($data, $x, $y);
    }

    /**
    *   add
    * 
    *   @param array $data
    *   @param int $x
    *   @param int $y
    *   @return self
    */
    public function add(
        array $data,
        int $x = 0,
        int $y = 0,
    ):self {
        $this->container[] = (new class($data, $x, $y) {
            public function __construct(
                public array $data,
                public int $x = 0,
                public int $y = 0,
            ) {}
        })($data, $x, $y);

        return $this->calcMaxAddress($data, $x, $y);
    }

    /**
    *   calcMaxAddress
    * 
    *   @param array $data
    *   @param int $x
    *   @param int $y
    *   @return self
    */
    private function calcMaxAddress(
        array $data,
        int $x,
        int $y,
    ):self {
        $this->calcMaxX($data, $x);
        $this->calcMaxY($data, $y);
        $this->dirty = true;
        return self;
    }

    /**
    *   calcMaxX
    * 
    *   @param array $data
    *   @param int $x
    *   @return void
    */
    private function calcMaxX(
        array $data,
        int $x,
    ):void {
        foreach($data as $row) {
            if (!is_array($row)) {
                $this->x = max($this->x, $x);
                continue;
            }

            $this->x = max(
                $this->x,
                ($x + count($row)),
            );
        }
    }

    /**
    *   calcMaxY
    * 
    *   @param array $data
    *   @param int $y
    *   @return void
    */
    private function calcMaxY(
        array $data,
        int $y,
    ):void {
        foreach(array_keys($data) as $key) {
            $column = array_column($data, $key);

            if (!is_array($column)) {
                $this->y = max($this->y, $y);
                continue;
            }

            $this->y = max(
                $this->y,
                ($x + count($column)),
            );
        }
    }

    /**
    *   toArray
    * 
    *   @return array
    */
    public function toArray():array
    {
        if ($this->dirty) {
            return $this->cache;
        }

        foreach($this->container as $dataset) {
            $this->cache[] = $this->createRange(
                $dataset->data,
                $dataset->x,
                $dataset->y,
            );
        }

        $this->container = [];
        
        $this->dirty = false;

        return $this->cache;
    }

    /**
    *   createRange
    * 
    *   @param array $data
    *   @param int $x
    *   @param int $y
    *   @return array
    */
    private function createRange(
        array $data,
        int $x,
        int $y,
    ): array {
        $result = [];

        for($i = 0; $i <= $this->y; $i++) {
            $result[] = $i < $y?
                $this->createInitRow():
                $this->fillColumn(
                    $data[$i]?? [],
                    $x,
                );
        }
        
        return $result;
    }

    /**
    *   fillColumn
    * 
    *   @param array $row
    *   @param int $x
    *   @return array
    */
    private function fillColumn(
        array $row,
        int $x,
    ): array {
        $values = is_array($row)? $row:[$row] 
        
        $keys = range(
            $x,
            $x + count($values),
        );

        return array_replace(
            $this->createInitRow(),
            array_combine($keys, $values),
        );
    }

    /**
    *   createInitRow
    * 
    *   @return array
    */
    private function createInitRow(): array
    {
        return array_fill(0, $tihs->x, null);
    }

    /**
    *   merge
    * 
    *   @param SheetRange $sheetRange 
    *   @return self
    */
    public function merge(
        SheetRange $sheetRange,
    ): self
    {
        return $this->add(
            $sheetRange->toArray(),
        );
    }
}

