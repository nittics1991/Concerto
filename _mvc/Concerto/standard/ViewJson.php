<?php

/**
*   VIEW JSON
*
*   @version 221214
*/

declare(strict_types=1);

namespace Concerto\standard;

use Concerto\standard\StringUtil;

class ViewJson
{
    /**
    *   @var string[]
    */
    protected array $headers = [];

    /**
    *   __construct
    *
    *   @param string[] $headers
    */
    public function __construct(
        array $headers = []
    ) {
        $this->headers = $headers;
    }

    /**
    *   header
    *
    */
    public function header(): void
    {
        foreach ($this->headers as $name => $val) {
            header(trim($name) . ':' . trim($val));
        }
    }

    /**
    *   render
    *
    *   @param mixed $dataset
    *   @return void
    */
    public function render(
        mixed $dataset = null
    ): void {
        $this->header();

        print(StringUtil::jsonEncode($dataset));
    }
}
