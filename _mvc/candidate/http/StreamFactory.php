<?php

/**
*   StreamFactory
*
*   @version 200527
*   @see https://github.com/zendframework/zend-diactoros
*/

declare(strict_types=1);

namespace candidate\http;

use InvalidArgumentException;
use Psr\Http\Message\{
    StreamFactoryInterface,
    StreamInterface
};
use candidate\http\Stream;

class StreamFactory implements StreamFactoryInterface
{
    /**
    * {@inheritDoc}
    */
    public function createStream(string $content = ''): StreamInterface
    {
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, $content);
        rewind($resource);

        return $this->createStreamFromResource($resource);
    }

    /**
    * {@inheritDoc}
    */
    public function createStreamFromFile(
        string $file,
        string $mode = 'r'
    ): StreamInterface {
        return new Stream($file, $mode);
    }

    /**
    * {@inheritDoc}
    */
    public function createStreamFromResource($resource): StreamInterface
    {
        if (
            ! is_resource($resource)
                || 'stream' !== get_resource_type($resource)
        ) {
            throw new InvalidArgumentException(
                'Invalid stream provided; must be a stream resource'
            );
        }
        return new Stream($resource);
    }
}
