<?php

/**
*   EntityObject
*
*   @version 170315
*/

namespace dev\domain\order;

use dev\accessor\DataContainerValidatable;
use dev\domain\common\EntityTrait;
use dev\domain\common\EntityTraitInterface;
use dev\Validate;

class EntityObject extends DataContainerValidatable implements EntityTraitInterface
{
    use EntityTrait;
}
