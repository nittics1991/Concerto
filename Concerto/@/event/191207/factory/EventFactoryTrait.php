<?php
/**
*   EventFactoryTrait
*
*   @version 191208
*/
declare('strict_type' = 1);

namespace Concerto\event\factory;

use Concerto\event\factory\EventFactoryTraitInterface;

trait EventFactoryTrait implements EventFactoryTraitInterface
{
    /**
    *   buildEvent
    * 
    *   @param array $context
    *   @return EventInterface
    **/
    public function buildEvent(
        array $context = [],
        bool $excludeThis = false,
        bool $excludeArgument = false,
        bool $excludeReturn = false
    ) : EventInterface {
        
        
        
        
    }
    
    
    
    
    
}
