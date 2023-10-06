<?php

/**
*   UriFactory
*
*   @version 200527
*   @see https://github.com/zendframework/zend-diactoros
*/

declare(strict_types=1);

namespace candidate\http;

use Psr\Http\Message\{
    UriFactoryInterface,
    UriInterface
};
use candidate\http\Uri;

class UriFactory implements UriFactoryInterface
{
    /**
    * {@inheritDoc}
    */
    public function createUri(string $uri = ''): UriInterface
    {
        return new Uri($uri);
    }
}
