<?php

/**
*  Construct
*
*   @ver 210813
**/

declare(strict_types=1);

namespace Concerto\container\construct;

interface ConstructContainerInterface
{

  /*
  *  bindParameters
  *
  *  @param string $name
  *  @param array $context
  *  @param ?bool $isPromptly
  *  @return ContainerInterface
  */
  public function bindParameters(
    string $name,
    array $context,
    ?bool $isPromptly = true
  ):ContainerInterface;
  
  /*
  *  build
  *
  *  @param string $name
  *  @return mixed
  */
  public function build(
    string $name
    ):mixed;
}
