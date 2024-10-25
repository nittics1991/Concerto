<?php

namespace Concerto\test\delegator;

interface ProjectInterface
{
    public function nonInjected(string $str);
    public function injected(MyInterface $obj);
} 
