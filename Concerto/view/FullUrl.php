<?php

/**
*    FullUrl
*
*   @version 181009
*/

declare(strict_types=1);

namespace Concerto\view;

use Concerto\standard\Invokable;
use Concerto\url\RealUrl;

class FullUrl implements Invokable
{
    /**
    *   parser
    *
    *   @var RealUrl
    */
    protected $parser;
    
    /**
    *   __construct
    *
    *   @param ?string $baseUrl
    */
    public function __construct(?string $baseUrl = null)
    {
        $this->parser = new RealUrl($baseUrl);
    }
    
    /**
    *   {inherit}
    */
    public function __invoke(...$args)
    {
        $url = $args[0] ?? '';
        return $this->parser->build($url);
    }
}
