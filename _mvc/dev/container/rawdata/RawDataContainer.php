<?php

/**
*   Raw Data Container
*
*   @ver 210813
**/

declare(strict_types=1);

namespace Concerto\container\rawdata;

use Concerto\container\rawdata\RawDataContainerInterface;
use Concerto\container\exception\NotFoundException;
use Psr\Container\ContainerInterface;

class RawDataContainer implements
    ContainerInterface,
    RawDataContainerInterface
{
    /**
    *   raws
    *
    *   @var array
    */
    protected $raws = [];
    
    /**
    *   {inherit}
    *
    **/
    public function raw($id, $concrete)
    {
        $this->raws[$id] = $concrete;
    }
    
    /**
    *   {inherit}
    *
    */
    public function get($id)
    {
      if ($this->has($id)) {
        throw new NotFoundException(
          "{$id} is not defined",
        );
        return $this->raws[$id];
    }
    
    /**
    *   {inherit}
    *
    **/
    public function has($id)
    {
      return array_key_exists($id, $this->raws);
    }
}
