<?php

//namespace Concerto\Valodator;

interface ReportFileFactoryInterface
{
    public function create(string $path): array;
}
