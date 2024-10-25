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
    ResponseFactoryInterface,
    ResponseInterface
};
use candidate\http\Response;

class ResponseFactory implements ResponseFactoryInterface
{
    /**
    * {@inheritDoc}
    */
    public function createResponse(
        int $code = 200,
        string $reasonPhrase = ''
    ): ResponseInterface {
        return (new Response())
            ->withStatus($code, $reasonPhrase);
    }
}
