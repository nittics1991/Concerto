<?php

/**
*   EntityObject
*
*   @version 170315
*/

namespace Concerto\domain\order;

use Concerto\accessor\DataContainerValidatable;
use Concerto\domain\common\EntityTrait;
use Concerto\domain\common\EntityTraitInterface;
use Concerto\Validate;

class EntityObject extends DataContainerValidatable implements EntityTraitInterface
{
    use EntityTrait;
}
