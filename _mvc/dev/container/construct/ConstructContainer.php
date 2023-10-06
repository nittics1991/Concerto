<?php

/**
*   Construct Container
*
*   @ver 210813
**/

declare(strict_types=1);

namespace Concerto\container\construct;

use Concerto\container\construct\ConstructContainerInterface;
use Concerto\container\rawdata\RawDataContainer;

class ConstructContainer extends RawDataContainer implements
    ConstructContainerInterface
{
    /**
    *  @var array 
    *
    **/
    private array parameters = [];

    /**
    *   {inherit}
    *
    **/
    public function bindParameters(
      string $name,
      array $context,
      ?bool $isPromptly = true
    ):ContainerInterface {
      foreach($context as $methodName => $parameters) {
        foreach($parameters as $parameterName => $value) {
          $bindName = "${name}.${methodName}.${parameterName}";

          if ($isPromptly &&
            !in_array($bindName, $this->parameters)
          ) {
              $this->parameters[] = $bindName;
          }
          $this->raw($bindName, $value);
        }
      }
      return $this;
  }

    /**
    *   {inherit}
    *
    **/
    public function build(
        string $name
    ):mixed {
        
        
        
        
        
        
        
    }
}
