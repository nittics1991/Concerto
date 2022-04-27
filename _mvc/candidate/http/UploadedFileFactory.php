<?php

/**
*   UploadedFileFactory
*
*   @version 200527
*   @see https://github.com/zendframework/zend-diactoros
*/

declare(strict_types=1);

namespace candidate\http;

use Psr\Http\Message\{
    UploadedFileFactoryInterface,
    StreamInterface,
    UploadedFileInterface
};
use candidate\http\UploadedFile;

class UploadedFileFactory implements UploadedFileFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createUploadedFile(
        StreamInterface $stream,
        int $size = null,
        int $error = UPLOAD_ERR_OK,
        string $clientFilename = null,
        string $clientMediaType = null
    ): UploadedFileInterface {
        if ($size === null) {
            $size = $stream->getSize();
        }

        return new UploadedFile(
            $stream,
            $size,
            $error,
            $clientFilename,
            $clientMediaType
        );
    }
}
