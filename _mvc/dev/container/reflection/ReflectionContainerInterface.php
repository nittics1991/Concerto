<?php


interface ReflectionContainerInterface
{
  /*
   *  @param string $name
   *  @param array $context
   *  @param ?bool $isPromptly
   *  @return ContainerInterface
   */
  public function bindParameters(
    string $name,
    array $context,
    ?bool $isPromptly = true
  ):ReflectionContainerInterface;
}

