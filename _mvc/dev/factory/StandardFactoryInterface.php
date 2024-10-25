<?php

namespace Concerto\pattern\builder;

interface StandardFactoryInterface
{
  /*
   *  make
   *
   *  @param string $namespace
   *  @param ?array $arguments
   *  @retun mixed
   */ 
  public function make(
    string $namespace,
    ?array $arguments
  ): mixed;
} 
