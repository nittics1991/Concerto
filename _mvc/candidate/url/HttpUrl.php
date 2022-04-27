<?php

/**
*   HttpUrl
*
*   @version 210614
*   @see https://github.com/spatie/url
*/

declare(strict_types=1);

namespace candidate\url;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;

class HttpUrl implements UriInterface
{
    /**
    *   SCHEMES
    *
    *   @const array
    */
    public const SCHEMES = ['http', 'https'];

    /**
    *   scheme
    *
    *   @var string
    */
    protected $scheme = '';

    /**
    *   host
    *
    *   @var string
    */
    protected $host = '';

    /**
    *   port
    *
    *   @var ?int
    */
    protected $port = null;

    /**
    *   user
    *
    *   @var string
    */
    protected $user = '';

    /**
    *   password
    *
    *   @var ?string
    */
    protected $password = null;

    /**
    *   path
    *
    *   @var string
    */
    protected $path = '';

    /**
    *   query
    *
    *   @var QueryParameter
    */
    protected $query;

    /**
    *   fragment
    *
    *   @var string
    */
    protected $fragment = '';

    /**
    *   __construct
    *
    */
    public function __construct()
    {
        $this->query = new QueryParameter();
    }

    /**
    *   create
    *
    *   @return HttpUrl
    */
    public static function create(): HttpUrl
    {
        return new self();
    }

    /**
    *   fromString
    *
    *   @param string $url
    *   @return HttpUrl
    */
    public static function fromString(string $url): HttpUrl
    {
        $parts = array_merge((array)parse_url($url));

        $url = new self();
        $url->scheme = isset($parts['scheme']) ?
            $url->sanitizeScheme((string)$parts['scheme']) : '';
        $url->host = (string)$parts['host'];
        $url->port = isset($parts['port']) ? (int)$parts['port'] : null;
        $url->user = (string)$parts['user'];
        $url->password = (string)$parts['pass'];
        $url->path = isset($parts['path']) ? (string)$parts['path'] : '/';
        $url->query = QueryParameter::fromString((string)$parts['query']);
        $url->fragment = (string)$parts['fragment'];

        return $url;
    }

    /**
    *   {inherit}
    *
    */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
    *   {inherit}
    *
    */
    public function getAuthority()
    {
        $authority = $this->host;

        if ($this->getUserInfo()) {
            $authority = $this->getUserInfo() . '@' . $authority;
        }

        if ($this->port !== null) {
            $authority .= ':' . $this->port;
        }
        return $authority;
    }

    /**
    *   {inherit}
    *
    */
    public function getUserInfo()
    {
        $userInfo = $this->user;

        if ($this->password !== null) {
            $userInfo .= ':' . $this->password;
        }
        return $userInfo;
    }

    /**
    *   {inherit}
    *
    */
    public function getHost()
    {
        return $this->host;
    }

    /**
    *   {inherit}
    *
    */
    public function getPort()
    {
        return $this->port;
    }

    /**
    *   {inherit}
    *
    */
    public function getPath()
    {
        return $this->path;
    }

    /**
    *   getBasename
    *
    *   @return string
    */
    public function getBasename(): string
    {
        return $this->getSegment(-1);
    }

    /**
    *   getDirname
    *
    *   @return string
    */
    public function getDirname(): string
    {
        $segments = $this->getSegments();
        array_pop($segments);
        return '/' . implode('/', $segments);
    }

    /**
    *   {inherit}
    *
    */
    public function getQuery(): string
    {
        return $this->query->__toString();
    }

    /**
    *   getQueryParameter
    *
    *   @param string $key
    *   @param ?string $default
    *   @return string
    */
    public function getQueryParameter(
        string $key,
        ?string $default = null
    ): string {
        return (string)$this->query->get($key, $default);
    }

    /**
    *   hasQueryParameter
    *
    *   @param string $key
    *   @return bool
    */
    public function hasQueryParameter(string $key): bool
    {
        return $this->query->has($key);
    }

    /**
    *   getAllQueryParameters
    *
    *   @return array
    */
    public function getAllQueryParameters(): array
    {
        return $this->query->all();
    }

    /**
    *   {inherit}
    *
    */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
    *   getSegments
    *
    *   @return array
    */
    public function getSegments(): array
    {
        return explode('/', trim($this->path, '/'));
    }

    /**
    *   getSegments
    *
    *   @param int $index
    *   @param ?string $default
    *   @return string
    */
    public function getSegment(int $index, ?string $default = null): string
    {
        $segments = $this->getSegments();

        if ($index === 0) {
            throw new InvalidArgumentException("segment zero does not exist");
        }

        if ($index < 0) {
            $segments = array_reverse($segments);
            $index = abs($index);
        }
        return $segments[$index - 1] ?? $default;
    }

    /**
    *   getFirstSegment
    *
    *   @return string
    */
    public function getFirstSegment()
    {
        $segments = $this->getSegments();
        return $segments[0] ?? null;
    }

    /**
    *   getLastSegment
    *
    *   @return string
    */
    public function getLastSegment()
    {
        $segments = $this->getSegments();
        return end($segments) ?? null;
    }

    /**
    *   {inherit}
    *
    */
    public function withScheme($scheme)
    {
        $url = clone $this;
        $url->scheme = $this->sanitizeScheme($scheme);
        return $url;
    }

    /**
    *   sanitizeScheme
    *
    *   @param string $scheme
    *   @return string
    */
    protected function sanitizeScheme(string $scheme): string
    {
        $scheme = strtolower($scheme);

        if (! in_array($scheme, static::SCHEMES)) {
            throw new InvalidArgumentException("invalid schema:{$scheme}");
        }
        return $scheme;
    }

    /**
    *   {inherit}
    *
    */
    public function withUserInfo($user, $password = null)
    {
        $url = clone $this;
        $url->user = $user;
        $url->password = $password;
        return $url;
    }

    /**
    *   {inherit}
    *
    */
    public function withHost($host)
    {
        $url = clone $this;
        $url->host = $host;
        return $url;
    }

    /**
    *   {inherit}
    *
    */
    public function withPort($port)
    {
        $url = clone $this;
        $url->port = $port;
        return $url;
    }

    /**
    *   {inherit}
    *
    */
    public function withPath($path)
    {
        $url = clone $this;

        if (strpos($path, '/') !== 0) {
            $path = '/' . $path;
        }
        $url->path = $path;
        return $url;
    }

    /**
    *   withDirname
    *
    *   @param string $dirname
    *   @return $this
    */
    public function withDirname(string $dirname)
    {
        $dirname = trim($dirname, '/');

        if (! $this->getBasename()) {
            return $this->withPath($dirname);
        }
        return $this->withPath($dirname . '/' . $this->getBasename());
    }

    /**
    *   withBasename
    *
    *   @param string $basename
    *   @return $this
    */
    public function withBasename(string $basename)
    {
        $basename = trim($basename, '/');

        if ($this->getDirname() === '/') {
            return $this->withPath('/' . $basename);
        }
        return $this->withPath($this->getDirname() . '/' . $basename);
    }

    /**
    *   {inherit}
    *
    */
    public function withQuery($query)
    {
        $url = clone $this;
        $url->query = QueryParameter::fromString($query);
        return $url;
    }

    /**
    *   withQueryParameter
    *
    *   @param string $key
    *   @param string $value
    *   @return $this
    */
    public function withQueryParameter(string $key, string $value)
    {
        $url = clone $this;
        $url->query->unset($key);
        $url->query->set($key, $value);
        return $url;
    }

    /**
    *   withoutQueryParameter
    *
    *   @param string $key
    *   @return $this
    */
    public function withoutQueryParameter(string $key)
    {
        $url = clone $this;
        $url->query->unset($key);
        return $url;
    }

    /**
    *   {inherit}
    *
    */
    public function withFragment($fragment)
    {
        $url = clone $this;
        $url->fragment = $fragment;
        return $url;
    }

    /**
    *   compare
    *
    *   @param self $url
    *   @return bool
    */
    public function compare(self $url): bool
    {
        return $this->__toString() === $url->__toString();
    }

    /**
    *   {inherit}
    *
    */
    public function __toString()
    {
        $url = '';

        if ($this->getScheme() !== '' && $this->getScheme() != 'mailto') {
            $url .= $this->getScheme() . '://';
        }

        if ($this->getScheme() === 'mailto' && $this->getPath() !== '') {
            $url .= $this->getScheme() . ':';
        }

        if ($this->getScheme() === '' && $this->getAuthority() !== '') {
            $url .= '//';
        }

        if ($this->getAuthority() !== '') {
            $url .= $this->getAuthority();
        }

        $url .= rtrim($this->getPath(), '/');

        if ($this->getQuery() !== '') {
            $url .= '?' . $this->getQuery();
        }

        if ($this->getFragment() !== '') {
            $url .= '#' . $this->getFragment();
        }
        return $url;
    }

    /**
    *   {inherit}
    *
    */
    public function __clone()
    {
        $this->query = clone $this->query;
    }
}
