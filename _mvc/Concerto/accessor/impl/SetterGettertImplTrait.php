<?php

/**
*   SetterGettertImplTrait
*
*   @version 190517
*/

declare(strict_types=1);

namespace Concerto\accessor\impl;

use BadMethodCallException;
use Concerto\accessor\GetterTrait;
use Concerto\accessor\SetterTrait;
use Concerto\accessor\impl\AttributeImplTrait;

trait SetterGettertImplTrait
{
    use AttributeImplTrait;
    use GetterTrait;
    use SetterTrait;

    /**
    *   {inherit}
    *
    */
    public function __call($name, $args)
    {
        if ($this->isGetterMethod($name)) {
            return $this->getter($name, $args);
        }

        if ($this->isSetterMethod($name)) {
            return $this->setter($name, $args);
        }

        throw new \BadMethodCallException(
            "not defined method:{$name}"
        );
    }
}
