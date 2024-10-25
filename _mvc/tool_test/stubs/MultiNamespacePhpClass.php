<?php

declare(strict_types=1);

namespace tool_test\stubs;

class MultiNamespacePhpClass
{
    public string $publicProp;

    protected int $protectedProp;

    private array $privateProp;

    public $noTypePublicProp;

    protected $noTypeProtectedProp;

    private $noTypePrivateProp;

    public function allHasTypeMethod(
        string $hasTypeParam1,
        int $hasTypeParam2,
        array $hasTypeParam3,
    ): array {
        return [];
    }

    public function hasNoTypeParamMethod(
        $noTypeParam1,
        int $hasTypeParam1,
        $noTypeParam2,
    ): array {
        return [];
    }

    public function allNoTypeMethod(
        $noTypeParam1,
        $noTypeParam2,
        $noTypeParam3,
    ) {
        return [];
    }
}

namespace dummy;

class DummyClass
{
}
