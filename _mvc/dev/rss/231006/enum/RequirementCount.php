<?php

/**
*   RequirementCount
*
*   @version 231006
*/

declare(strict_types=1);

namespace Concerto;

enum RequirementCount
{
    case ONLY;
    case ANY;
    case ZERO_OR_MORE;
    case ONE_OR_MORE;
    case NTH_OR_MORE;
    case NTH_OR_LESS;
}
