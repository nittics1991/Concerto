<?php
/**
*
*
*
**/
namespace Concerto\event\exception;

use RuntimeException;

class InvalidListenerException extends RuntimeException implements ExceptionInterface
{
    /**
     * @param mixed $service Should be a non-object type.
     */
    public static function forNonCallableService($service) : self
    {
        return new self(sprintf(
            'Lazy listener of type "%s" is invalid; must be a PHP callable',
            gettype($service)
        ));
    }

    /**
     * @param mixed $service Should be an object.
     */
    public static function forNonCallableInstance($service) : self
    {
        return new self(sprintf(
            'Lazy listener of type "%s" is invalid; must be a callable, or have a method associated with it',
            get_class($service)
        ));
    }

    /**
     * @param mixed $service Should be an object.
     */
    public static function forNonCallableInstanceMethod($service, string $method) : self
    {
        return new self(sprintf(
            'Lazy listener of type "%s" with associated method "%s" is invalid; not callable',
            get_class($service),
            $method
        ));
    }
}
