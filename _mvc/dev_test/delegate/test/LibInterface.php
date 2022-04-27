<?php

namespace dev_test\test\delegator;

interface LibInterface
{
    public function nonInjected(string $str);
    public function injected(LibInterface $obj);
    public function getLibId();
} 
