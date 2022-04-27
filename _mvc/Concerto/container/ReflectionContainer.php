<?php

/**
*   Service Container
*
*   @version 220208
*   @see https://github.com/ecfectus/container
*/

declare(strict_types=1);

namespace Concerto\container;

use ReflectionClass;
use ReflectionIntersectionType;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionUnionType;
use Concerto\container\ContainerAwareInterface;
use Concerto\container\exception\{
    ContainerException,
    NotFoundException,
};
use Psr\Container\ContainerInterface;

class ReflectionContainer implements
    ContainerInterface,
    ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
    *   {inherit}
    *
    */
    public function get($id)
    {
        if (!class_exists($id)) {
            throw new NotFoundException(
                "{$id} is not an existing class"
            );
        }

        $reflector = new ReflectionClass($id);
        $construct = $reflector->getConstructor();
        if ($construct === null) {
            return new $id();
        }

        return $reflector->newInstanceArgs(
            $this->reflectArguments($construct)
        );
    }

    /**
    *   reflectArguments
    *
    *   @param ReflectionMethod $method
    *   @return mixed[]
    */
    private function reflectArguments(ReflectionMethod $method)
    {
        $arguments = array_map(
            function (ReflectionParameter $param) {

                //php8.1対応
                $name  = $param->getName();
                $type = $param->getType();

                if ($type === null) {
                    throw new NotFoundException(
                        "Unable to resolve a parameter:{$name}",
                    );
                }

                if (
                    $type instanceof ReflectionIntersectionType ||
                    $type instanceof ReflectionUniType
                ) {
                    throw new ContainerException(
                        "does not supporte type:{$name}"
                    );
                }

                return $param->isDefaultValueAvailable() ?
                    $param->getDefaultValue() :
                    $type->getName();
            },
            $method->getParameters()
        );

        return $this->resolveArguments($arguments);
    }

    /**
    *   resolveArguments
    *
    *   @param array $arguments
    *   @return mixed[]
    */
    private function resolveArguments(array $arguments): mixed
    {
        foreach ($arguments as &$arg) {
            if (!is_string($arg)) {
                continue;
            }
            $container = $this->getContainer();

            if ($container->has($arg)) {
                $arg = $container->get($arg);
                continue;
            }
        }
        return $arguments;
    }

    /**
    *   {inherit}
    *
    */
    public function has($id): bool
    {
        return class_exists($id);
    }
}
