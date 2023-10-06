<?php

/**
*   StreamFactory
*
*   @version 200527
*   @see https://github.com/zendframework/zend-diactoros
*/

declare(strict_types=1);

namespace candidate\http;

use Psr\Http\Message\{
    RequestFactoryInterface,
    RequestInterface
};
use candidate\http\Request;

class RequestFactory implements RequestFactoryInterface
{
    /**
    * {@inheritDoc}
    */
    public function createRequest(string $method, $uri): RequestInterface
    {
        return new Request($uri, $method);
    }
}
