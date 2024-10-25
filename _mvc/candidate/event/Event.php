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
    *   @inheritDoc
    *
    */
    public function getName()
    {
        return $this->name;
    }

    /**
    *   @inheritDoc
    *
    */
    public function getTarget()
    {
        return $this->target;
    }

    /**
    *   @inheritDoc
    *
    */
    public function getParams()
    {
        return $this->params;
    }

    /**
    *   @inheritDoc
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
    *   @inheritDoc
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
    *   @inheritDoc
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
    *   @inheritDoc
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
    *   @inheritDoc
    *
    */
    public function stopPropagation($flag)
    {
        $this->propagationStopped = ($flag) ? true : false;
    }

    /**
    *   @inheritDoc
    *
    */
    public function isPropagationStopped()
    {
        return $this->propagationStopped;
    }
}
