<?php


//ReflectionContainerの作るか?
//その場合raw()もここに
//interfaceはどうする?
//
//ServiceContainerに組み込むか?
//



//bindParametersはclass method用のパラメータをbindする
//基本的にdomain中でbindParameterする
//isPrompty == false raw()と同じ
//isPrompty == true $container のpropertyに新規array作成
//get()にcallされたら 削除する処理を追加する




$container->bindParameters(
  ClassNamespace::class,
  parameters = [
    methodName1 => [
      parameterName11 => $value11,
      parameterName12 => $value12,
    ],
    methodName2 => [
      parameterName21 => $value11,
      parameterName22 => $value12,
    ],
  ]
  true,
);

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
  }

