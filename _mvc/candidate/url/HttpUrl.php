<?php

/**
*   HttpUrl
*
*   @version 230418
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
    protected string $scheme = '';

    /**
    *   host
    *
    *   @var string
    */
    protected string $host = '';

    /**
    *   port
    *
    *   @var ?int
    */
    protected ?int $port = null;

    /**
    *   user
    *
    *   @var string
    */
    protected string $user = '';

    /**
    *   password
    *
    *   @var ?string
    */
    protected ?string $password = null;

    /**
    *   path
    *
    *   @var string
    */
    protected string $path = '';

    /**
    *   query
    *
    *   @var QueryParameter
    */
    protected QueryParameter $query;

    /**
    *   fragment
    *
    *   @var string
    */
    protected string $fragment = '';

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
    *   @inheritDoc
    *
    */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
    *   @inheritDoc
    *
    */
    public function getAuthority(): string
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
    *   @inheritDoc
    *
    */
    public function getUserInfo(): string
    {
        $userInfo = $this->user;

        if ($this->password !== null) {
            $userInfo .= ':' . $this->password;
        }
        return $userInfo;
    }

    /**
    *   @inheritDoc
    *
    */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
    *   @inheritDoc
    *
    */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
    *   @inheritDoc
    *
    */
    public function getPath(): string
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
    *   @inheritDoc
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
    *   @return string[]
    */
    public function getAllQueryParameters(): array
    {
        return $this->query->all();
    }

    /**
    *   @inheritDoc
    *
    */
    public function getFragment():string
    {
        return $this->fragment;
    }

    /**
    *   getSegments
    *
    *   @return string[]
    */
    public function getSegments(): array
    {
        return explode('/', trim($this->path, '/'));
    }

    /**
    *   getSegment
    *
    *   @param int $index
    *   @param string $default
    *   @return string
    */
    public function getSegment(int $index, string $default = ''): string
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
    *   @return ?string
    */
    public function getFirstSegment():?string
    {
        $segments = $this->getSegments();
        return $segments[0] ?? null;
    }

    /**
    *   getLastSegment
    *
    *   @return ?string
    */
    public function getLastSegment():?string
    {
        $segments = $this->getSegments();
        return end($segments) === false?
            null:end($segments);
    }

    /**
    *   @inheritDoc
    *
    */
    public function withScheme(string $scheme): UriInterface
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
    *   @inheritDoc
    *
    */
    public function withUserInfo(string $user, ?string $password = null): UriInterface
    {
        $url = clone $this;
        $url->user = $user;
        $url->password = $password;
        return $url;
    }

    /**
    *   @inheritDoc
    *
    */
    public function withHost(string $host): UriInterface
    {
        $url = clone $this;
        $url->host = $host;
        return $url;
    }

    /**
    *   @inheritDoc
    *
    */
    public function withPort(?int $port): UriInterface
    {
        $url = clone $this;
        $url->port = $port;
        return $url;
    }

    /**
    *   @inheritDoc
    *
    */
    public function withPath(string $path): UriInterface
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
    *   @return UriInterface
    */
    public function withDirname(string $dirname): UriInterface
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
    *   @return UriInterface
    */
    public function withBasename(string $basename):UriInterface
    {
        $basename = trim($basename, '/');

        if ($this->getDirname() === '/') {
            return $this->withPath('/' . $basename);
        }
        return $this->withPath($this->getDirname() . '/' . $basename);
    }

    /**
    *   @inheritDoc
    *
    */
    public function withQuery(string $query): UriInterface
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
    *   @inheritDoc
    *
    */
    public function withFragment(string $fragment): UriInterface
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
    *   @inheritDoc
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
    *   @inheritDoc
    *
    */
    public function __clone()
    {
        $this->query = clone $this->query;
    }
}
