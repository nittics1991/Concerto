<?php

/**
*   VIEW JSON
*
*   @version 210610
*/

declare(strict_types=1);

namespace Concerto\standard;

use Concerto\standard\StringUtil;

class ViewJson
{
    /**
    *   headers
    *
    *   @var array
    */
    protected array $headers = [];

    /**
    *   __construct
    *
    *   @param array $headers
    */
    public function __construct(array $headers = [])
    {
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
    *   @param ?mixed $dataset
    */
    public function render($dataset = null): void
    {
        $this->header();
        print(StringUtil::jsonEncode($dataset));
    }
}
