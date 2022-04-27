<?php

/**
*   Service Container
*
*   @version 210903
*   @see https://github.com/ecfectus/container
*/

declare(strict_types=1);

namespace Concerto\container;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Concerto\container\ContainerRegisterInterface;
use Concerto\container\exception\{
    ContainerException,
    NotFoundException
};

class ServiceContainer implements
    ContainerInterface,
    ContainerRegisterInterface
{
    /**
    *   definitions
    *
    *   @var mixed[]
    */
    protected $definitions = [];

    /**
    *   sharedDefinitions
    *
    *   @var mixed[]
    */
    protected $sharedDefinitions = [];

    /**
    *   sharedInstances
    *
    *   @var mixed[]
    */
    protected $sharedInstances = [];

    /**
    *   delegates
    *
    *   @var ContainerInterface[]
    */
    protected $delegates = [];

    /**
    *   extenders
    *
    *   @var callable[]
    */
    protected $extenders = [];

    /**
    *   raws
    *
    *   @var mixed[]
    */
    protected $raws = [];

    /**
    *   {inherit}
    */
    public function get(
        string $id
    ) {
        if (array_key_exists($id, $this->raws)) {
            return $this->raws[$id];
        }

        if (array_key_exists($id, $this->sharedInstances)) {
            return $this->sharedInstances[$id];
        }

        if (array_key_exists($id, $this->sharedDefinitions)) {
            $instance = $this->makeFromDefinition(
                $this->sharedDefinitions[$id]
            );
            $instance = $this->applyExtenders($id, $instance);
            $this->sharedInstances[$id] = $instance;
            return $instance;
        }

        if (array_key_exists($id, $this->definitions)) {
            $instance = $this->makeFromDefinition($this->definitions[$id]);
            $instance = $this->applyExtenders($id, $instance);
            return $instance;
        }

        if ($resolved = $this->getFromDelegate($id)) {
            $resolved = $this->applyExtenders($id, $resolved);
            return $resolved;
        }
        throw new NotFoundException(
            "{$id} is not being managed by the container"
        );
    }

    /**
    *   {inherit}
    */
    public function has(string $id): bool
    {
        //notes:bind()のconcreteに配列で定義した時、引数にarrayが使えない
        //example:bind('name', [function($arg){
        //  return new ArrayObject($arg);}, [1, 2, 3]]
        //);
        if (!is_string($id)) {
            return false;
        }
        //ここまで

        if (array_key_exists($id, $this->raws)) {
            return true;
        }

        if (array_key_exists($id, $this->definitions)) {
            return true;
        }

        if (array_key_exists($id, $this->sharedDefinitions)) {
            return true;
        }

        if (array_key_exists($id, $this->sharedInstances)) {
            return true;
        }
        return $this->hasInDelegate($id);
    }

    /**
    *   {inherit}
    */
    public function bind(
        string $id,
        mixed $concrete = null,
        bool $shared = false
    ) {
        if (null === $concrete) {
            $concrete = $id;
        }
        $id = $this->normalizeString($id);

        if (is_object($concrete) && !$concrete instanceof \Closure) {
            $instance = $this->applyExtenders($id, $concrete);
            $this->sharedInstances[$id] = $instance;
            return;
        }
        $concrete = $this->normalizeString($concrete);

        //定数arrayをbindする時、$container[0]が無いと言われる
        // if (is_callable($concrete)
        //    || (is_array($concrete) && is_callable($concrete[0]))
        if (
            is_callable($concrete) ||
            (
                is_array($concrete) &&
                isset($concrete[0]) &&
                is_callable($concrete[0])
            )
        ) {
            if (false === $shared) {
                $this->definitions[$id] = (array) $concrete;
                return;
            }
            $this->sharedDefinitions[$id] = (array) $concrete;
            return;
        }

        //定数arrayをbindする時、$container[0]が無いと言われる
        // if (is_string($concrete) && class_exists($concrete)
        //    || (is_array($concrete) && class_exists($concrete[0]))
        if (
            is_string($concrete) &&
            class_exists($concrete) ||
            (
                is_array($concrete) &&
                isset($concrete[0]) &&
                class_exists((string)$concrete[0])
            )
        ) {
            if (false === $shared) {
                $this->definitions[$id] = (array) $concrete;
                return;
            }
            $this->sharedDefinitions[$id] = (array) $concrete;
            return;
        }
        $this->sharedInstances[$id] = $concrete;
    }

    /**
    *   {inherit}
    */
    public function share(
        string $id,
        mixed $concrete = null
    ) {
        return $this->bind($id, $concrete, true);
    }

    /**
    *   {inherit}
    *
    *   @param string $id
    *   @param callable $extender fn($instance, $this)
    */
    public function extend(
        string $id,
        callable $extender
    ) {
        if (!$this->has($id)) {
            throw new NotFoundException(
                "{$id} is not being managed by the container"
            );
        }
        $this->extenders[$id][] = $extender;
    }

    /**
    *   delegate
    *
    *   @param ContainerInterface $container
    *   @return $this
    */
    public function delegate(
        ContainerInterface $container
    ) {
        $this->delegates[] = $container;
        if ($container instanceof ContainerAwareInterface) {
            $container->setContainer($this);
        }
        return $this;
    }

    /**
    *   hasInDelegate
    *
    *   @param string $id
    *   @return bool
    */
    private function hasInDelegate($id)
    {
        foreach ($this->delegates as $container) {
            if ($container->has($id)) {
                return true;
            }
        }
        return false;
    }

    /**
    *   getFromDelegate
    *
    *   @param string $id
    *   @return mixed
    */
    protected function getFromDelegate(
        string $id
    ): mixed {
        foreach ($this->delegates as $container) {
            if ($container->has($id)) {
                return $container->get($id);
            }
            continue;
        }
        return false;
    }

    /**
    *   makeFromDefinition
    *
    *   @param array $definition
    *   @return mixed
    */
    private function makeFromDefinition(
        array $definition
    ): mixed {
        $target = array_shift($definition);

        //Closureは$thisが引数
        if (empty($definition)) {
            //引数無しfunctionが動かない
            if ($target instanceof \Closure) {
                return $target($this);
            }
            //ここまで

            if (is_callable($target)) {
                return $target();
            }

            if (class_exists($target)) {
                $instance = new $target();
                return $instance;
            }
            //return false;
            throw new ContainerException(
                "definition stack is not callable"
            );
        }

        foreach ($definition as $key => $value) {
            if (is_string($value) && $this->has($value)) {
                $definition[$key] = $this->get($value);
                continue;
            }
        }

        if (is_callable($target)) {
            return $target(...$definition);
        }

        $str_target = is_object($target) ?
            get_class($target) :
            strval($target);

        if (class_exists($str_target)) {
            return new $str_target(...$definition);
        }
        //return false;
        throw new ContainerException(
            "definition stack is not callable"
        );
    }

    /**
    *   normalizeString
    *
    *   @param mixed $string
    *   @return mixed
    */
    private function normalizeString(
        mixed $string
    ) {
        return (is_string($string) && strpos($string, '\\') === 0) ?
        substr($string, 1) : $string;
    }

    /**
    *   applyExtenders
    *
    *   @param mixed $id
    *   @param mixed $instance
    *   @return mixed
    */
    private function applyExtenders(
        mixed $id,
        mixed $instance
    ): mixed {
        if (
            isset($this->extenders[$id]) &&
            !empty($this->extenders[$id]) &&
            is_array($this->extenders[$id])
        ) {
            foreach ($this->extenders[$id] as $extender) {
                $instance = $extender($instance, $this);
            }
        }
        return $instance;
    }

    /**
    *   __call
    *
    *   @param string $name method
    *   @param mixed[] $arguments
    *   @return mixed
    */
    public function __call(
        string $name,
        array $arguments,
    ): mixed {
        foreach ($this->delegates as $container) {
            if (is_callable([$container, $name])) {
                return $container->$name(...$arguments);
            }
        }
        return null;
    }

    /**
    *   raw
    *
    *   @param string $id
    *   @param mixed $concrete
    */
    public function raw(
        string $id,
        mixed $concrete
    ) {
        if ($concrete instanceof \Closure) {
            throw new InvalidArgumentException(
                "required scala,array,resource,object"
            );
        }
        $this->raws[$id] = $concrete;
    }
}
