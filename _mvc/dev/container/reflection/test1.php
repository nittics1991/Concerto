<?php

$reflectionParameters = $reflectionMethod->getParameter();
$resolvedParameters = [];

foreach($reflectionParameters as $reflectionParameter) {
  $parameterName = $reflectionParameter->getName();

  //reflectionClass,reflectionMethodが必要
  $bindedParameterName = $reflectionClassName .
    ".${reflectionMethodName}" . //__constructになる
    ".${parameterName}";

  if ($this->container->has($bindedParameterName)) {
    $resolvedParameters[] = $this->container->get(
      $bindedParameterName
    );
    continue;
  }

  $reflectionTypes = $reflectionParameter->getType();
    
  $reflectionUnionTypes =
    $reflectionTypes instanceof ReflectionUnionType ?
        $reflectionTypes:
        [$reflectionTypes];

    foraech($reflectionUnionTypes as $reflectionUnionType) {
    
    
    
    
      $type = $reflectionUnionType->getName();

      if ($reflectionUnionType->isBuiltin()) {
        if ($type === 'string') {
          
          
          
          
          if ($this->container->has($parameterName)) {
            $resolvedParameters[] =
              $this->container->get($parameterName);






