<?php

/**
*   ExcelNode
*
*   @version 240919
*/

declare(strict_types=1);

namespace Concerto\excel\parts;

class ExcelNode
{
    /**
    *   @var string
    */
    public string $name;

    /**
    *   @var string[]
    */
    public array $attribute = [];

    /**
    *   @var ?string
    */
    public ?string $text = null;

    /**
    *   @var ExcelNode[]
    */
    public array $children = [];
}
