<?php

/**
*   Real URL
*
*   @version 230418
*/

declare(strict_types=1);

namespace candidate\url;

use InvalidArgumentException;
use Concerto\standard\Server;

class RealUrl
{
    /**
    *   SCHEME
    *
    *   @const string[]
    */
    public const SCHEME = ['http', 'https'];

    /**
    *   baseUrl
    *
    *   @var string
    */
    protected $baseUrl;

    /**
    *   parsedUrl
    *
    *   @var mixed[]
    */
    protected array $parsedUrl;

    /**
    *   origin
    *
    *   @var string
    */
    protected $origin;

    /**
    *   __construct
    *
    *   @param ?string $baseUrl
    */
    public function __construct(?string $baseUrl = null)
    {
        $baseUrl = ($baseUrl) ? $baseUrl : Server::getRequestUrl();
        $this->parsedUrl = (array)parse_url($baseUrl);
        $this->baseUrl = $this->createBaseUrl($baseUrl);

        $urls = explode('/', $this->baseUrl);
        $this->origin = (empty($urls[2])) ?
            $this->baseUrl :
            $urls[0] . '//' . $urls[2];
    }

    /**
    *   createBaseUrl
    *
    *   @param string $path
    *   @return string
    */
    protected function createBaseUrl(string $path): string
    {
        if (($pos = mb_strpos($path, '?')) !== false) {
            $path = mb_substr($path, 0, $pos);
        }
        if (($pos = mb_strpos($path, '#')) !== false) {
            $path = mb_substr($path, 0, $pos);
        }
        return $path;
    }

    /**
    *   build
    *
    *   @param string $path
    *   @return string
    */
    public function build(string $path): string
    {
        $path = trim($path);

        //full path
        $lowerPath = strtolower($path);
        $scheme = explode(':', $lowerPath);
        if (in_array($scheme[0], self::SCHEME)) {
            return $path;
        }

        //anchor
        if (mb_strpos($path, '#') === 0) {
            return $this->baseUrl . $path;
        }

        //query
        if (mb_strpos($path, '?') === 0) {
            return $this->baseUrl . $path;
        }

        $basePath = (isset($this->parsedUrl['path'])) ?
            strval($this->parsedUrl['path']) : '/';

        //path
        if (mb_strpos($path, '/') === 0) {
            return $this->origin . $path;
        }

        $parsedBasePath = array_filter(
            explode('/', $basePath),
            fn($val) => (bool)strlen($val),
        );

        //relative path(./ or ../)
        if (mb_strpos((string)end($parsedBasePath), '.') !== false) {
            array_pop($parsedBasePath);
        }

        foreach (explode('/', $path) as $pathElem) {
            if ($pathElem === '.') {
                //nop
            } elseif ($pathElem === '..') {
                array_pop($parsedBasePath);
            } elseif ($pathElem !== '') {
                $parsedBasePath[] = $pathElem;
            }
        }

        $url = $this->origin . '/' . implode('/', $parsedBasePath);
        if (substr($path, -1) === '/') {
            $url .= '/';
        }
        return $url;
    }
}
