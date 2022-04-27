<?php

/**
*   Event
*
*   @version 170220
*/

declare(strict_types=1);

namespace candidate\event;

//use Psr\EventManager\EventInterface;
use candidate\event\EventInterface;
use InvalidArgumentException;

class Event implements EventInterface
{
    /**
    *   propagationStopped
    *
    *   @var bool
    */
    private $propagationStopped = false;

    /**
    *   name
    *
    *   @var string
    */
    private $name;

    /**
    *   target
    *
    *   @var mixed
    */
    private $target;

    /**
    *   params
    *
    *   @var mixed[]
    */
    private $params;

    /**
    *   constructor
    *
    *   @param string $name
    *   @param null|string|object $target
    *   @param mixed[] $params
    */
    public function __construct($name = null, $target = null, $params = null)
    {
        if (!is_null($name)) {
            $this->setName($name);
        }

        if (!is_null($target)) {
            $this->setTarget($target);
        }

        if (!is_null($params)) {
            $this->setParams($params);
        }
    }

    /**
    *   {inherit}
    *
    */
    public function getName()
    {
        return $this->name;
    }

    /**
    *   {inherit}
    *
    */
    public function getTarget()
    {
        return $this->target;
    }

    /**
    *   {inherit}
    *
    */
    public function getParams()
    {
        return $this->params;
    }

    /**
    *   {inherit}
    *
    */
    public function getParam($name)
    {
        if (!isset($this->params[$name])) {
            throw new InvalidArgumentException("not has parameter:{$name}");
        }
        return $this->params[$name];
    }

    /**
    *   {inherit}
    *
    */
    public function setName($name)
    {
        if (!is_string($name)) {
            throw new InvalidArgumentException("must be type string");
        }
        $this->name = $name;
    }

    /**
    *   {inherit}
    *
    */
    public function setTarget($target)
    {
        if (!is_string($target) && !is_object($target) && !is_null($target)) {
            throw new InvalidArgumentException("type missing");
        }
        $this->target = $target;
    }

    /**
    *   {inherit}
    *
    */
    public function setParams(array $params)
    {
        if (!is_array($params)) {
            throw new InvalidArgumentException("must be type array");
        }
        $this->params = $params;
    }

    /**
    *   {inherit}
    *
    */
    public function stopPropagation($flag)
    {
        $this->propagationStopped = ($flag) ? true : false;
    }

    /**
    *   {inherit}
    *
    */
    public function isPropagationStopped()
    {
        return $this->propagationStopped;
    }
}
