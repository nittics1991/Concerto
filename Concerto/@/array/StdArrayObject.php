<?php

/**
*   StdArrayObject
*
*   @version 210709
*/

declare(strict_types=1);

namespace Concerto\array;

use Throwable;


//use RuntimeException;

class StdArrayObject
{
    /**
    *   dataset
    *
    *   @var array
    */
    private $dataset;
    
    /**
    *   __construct
    *
    *   @param ?array $data
    */
    public function __construct(
        ?array $data
    ) {
        $this->dataset = $data?? [];
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
    
    /**
    *   changeKeyCase
    *
    *   @param ?int $case
    *   @return array
    */
    public function changeKeyCase(
        ?int $case,
    ): static
    {
        return new static(
            array_change_key_case(
                $this->dataset,
                $case?? CASE_LOWER,
            )
        );
    }
    
    /**
    *   changeKeyLowerCase
    *
    *   @return array
    */
    public function changeKeyLowerCase(
    ): static
    {
        return $this->changeKeyCase(
            CASE_LOWER,
        );
    }
    
    /**
    *   changeKeyUpperCase
    *
    *   @return array
    */
    public function changeKeyUpperCase(
    ): static
    {
        return $this->changeKeyCase(
            CASE_UPPER,
        );
    }
    
    /**
    *   chunk
    *
    *   @param int $length
    *   @param ?bool $preserve_keys
    *   @return array
    */
    public function chunk(
        int $length,
        ?bool $preserve_keys,
    ): static
    {
        try {
            return new static(
                (array)array_chunk(
                    $this->dataset,
                    $length
                    $preserve_keys?? false,
                )
            );
        } catch(Throwable $t) {
            throw new InvalidArgumentException(
            
            )
            
        }
        
        
        
        
    }

    
    
    
    
    
    
    
    
    
}
