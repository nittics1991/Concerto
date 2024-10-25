<?php

/**
*   RequirementLevel
*
*   @version 231006
*/

declare(strict_types=1);

namespace Concerto;

enum RequirementLevel
{
    case MUST;
    case MUST_NOT;
    case REQUIRED;
    case SHALL;
    case SHALL_NOT;
    case SHOULD;
    case SHOULD_NOT;
    case RECOMMENDED;
    case MAY;
    case OPTIONAL;
}
