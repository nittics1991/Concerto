<?php

/**
*   PhpInputStream
*
*   @version 200527
*   @see https://github.com/zendframework/zend-diactoros
*/

declare(strict_types=1);

namespace candidate\http;

use candidate\http\Stream;

class PhpInputStream extends Stream
{
    /**
     * @var string
     */
    private $cache = '';

    /**
     * @var bool
     */
    private $reachedEof = false;

    /**
     * @param  string|resource $stream
     */
    public function __construct($stream = 'php://input')
    {
        parent::__construct($stream, 'r');
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        if ($this->reachedEof) {
            return $this->cache;
        }

        $this->getContents();
        return $this->cache;
    }

    /**
     * {@inheritdoc}
     */
    public function isWritable(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function read($length): string
    {
        $content = parent::read($length);
        if (! $this->reachedEof) {
            $this->cache .= $content;
        }

        if ($this->eof()) {
            $this->reachedEof = true;
        }

        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function getContents($maxLength = -1): string
    {
        if ($this->reachedEof) {
            return $this->cache;
        }

        $contents     = stream_get_contents($this->resource, $maxLength);
        $this->cache .= $contents;

        if ($maxLength === -1 || $this->eof()) {
            $this->reachedEof = true;
        }

        return $contents;
    }
}
