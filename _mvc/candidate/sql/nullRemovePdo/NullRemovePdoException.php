<?php

/**
*   NullRemovePdoException
*
*   @version 230405
**/

declare(strict_types=1);

namespace candidate\sql\nullRemovePdo;

use RuntimeException;
use Throwable;

class NullRemovePdoException extends RuntimeException
{
    /**
    *   @var mixed
    */
    private mixed $context;

    /**
    *   __construct
    *
    *   @param string $message
    *   @param int|string $code
    *   @param ?Throwable $previous
    *   @param mixed $context
    **/
    public function __construct(
        string $message = "",
        int|string $code = 0,
        ?Throwable $previous = null,
        mixed $context = null,
    ) {
        parent::__construct(
            $message,
            intval($code),
            $previous,
        );

        $this->context = $context;
    }

    /**
    *   create
    *
    *   @param Throwable $exception
    *   @param mixed $context
    *   @return self
    **/
    public static function create(
        Throwable $exception,
        mixed $context = null,
    ): self {
        return new self(
            $exception->getMessage(),
            $exception->getCode(),
            $exception,
            $context,
        );
    }

    /**
    *   getContext
    *
    *   @return mixed
    **/
    public function getContext(): mixed
    {
        return $this->context;
    }
}
