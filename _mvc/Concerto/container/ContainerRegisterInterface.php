<?php

/**
*   ContainerRegisterInterface
*
*   @version 220122
*/

declare(strict_types=1);

namespace Concerto\container;

use Psr\Container\ContainerInterface;

interface ContainerRegisterInterface
{
    /**
    *   bind
    *
    *   @param string $id
    *   @param mixed $concrete
    *   @param bool $shared
    */
    public function bind(
        string $id,
        mixed $concrete,
        bool $shared
    );

    /**
    *   share
    *
    *   @param string $id
    *   @param mixed $concrete
    */
    public function share(
        string $id,
        mixed $concrete
    );

    /**
    *   extend
    *
    *   @param string $id
    *   @param callable $extender fn($instance, $this)
    */
    public function extend(
        string $id,
        callable $extender
    );

    /**
    *   delegate
    *
    *   @param ContainerInterface $container
    */
    public function delegate(
        ContainerInterface $container
    );
}
