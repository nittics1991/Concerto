<?php
declare(type_stricts=1);

namespace Concerto\accessor\reflectable;

interface ReflectePropertyTraitInterface
{
    /**
    *   classのpropertyを解析し、propertiesを定義
    *
    */
    protected function reflecteProperty();
}

