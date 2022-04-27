<?php

/**
*   Request
*
*   @version 200527
*   @see https://github.com/zendframework/zend-diactoros
*/

declare(strict_types=1);

namespace candidate\http;

use Psr\Http\Message\{
    RequestInterface,
    StreamInterface,
    UriInterface
};
use candidate\http\RequestTrait;

class Request implements RequestInterface
{
    use RequestTrait;

    /**
     * @param null|string|UriInterface $uri URI for the request, if any.
     * @param null|string $method HTTP method for the request, if any.
     * @param string|resource|StreamInterface $body Message body, if any.
     * @param array $headers Headers for the message, if any.
     */
    public function __construct(
        $uri = null,
        string $method = null,
        $body = 'php://temp',
        array $headers = []
    ) {
        $this->initialize($uri, $method, $body, $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders(): array
    {
        $headers = $this->headers;
        if (
            ! $this->hasHeader('host')
            && $this->uri->getHost()
        ) {
            $headers['Host'] = [$this->getHostFromUri()];
        }

        return $headers;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader($header): array
    {
        if (! $this->hasHeader($header)) {
            if (
                strtolower($header) === 'host'
                && $this->uri->getHost()
            ) {
                return [$this->getHostFromUri()];
            }

            return [];
        }

        $header = $this->headerNames[strtolower($header)];

        return $this->headers[$header];
    }
}
