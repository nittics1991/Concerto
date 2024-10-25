<?php

/**
*   NotFoundException
*
*   @ver 170208
**/

declare(strict_types=1);

namespace Concerto\container\exception;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends Exception implements NotFoundExceptionInterface
{
}
