<?php

/**
*   QueryParameter
*
*   @version 210607
*/

declare(strict_types=1);

namespace candidate\url;

class QueryParameter
{
    /**
    *   scheme
    *
    *   @var string[]
    */
    protected array $parameters;

    /**
    *   __construct
    *
    *   @param string[] $parameters
    */
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    /**
    *   fromString
    *
    *   @param string $query
    *   @return QueryParameter
    */
    public static function fromString(string $query = ''): self
    {
        if ($query === '') {
            return new self();
        }

        $parameters = [];
        foreach (explode('&', $query) as $keyValue) {
            $parts = explode('=', $keyValue, 2);
            $parameters[$parts[0]] = ($parts[1]) ?? $parts;
        }
        return new self($parameters);
    }

    /**
    *   get
    *
    *   @param string $key
    *   @param ?string $default
    *   @return string|null
    */
    public function get(string $key, ?string $default = null)
    {
        return ($this->parameters[$key]) ?? $default;
    }

    /**
    *   has
    *
    *   @param string $key
    *   @return bool
    */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->parameters);
    }

    /**
    *   set
    *
    *   @param string $key
    *   @param string $value
    *   @return $this
    */
    public function set(string $key, string $value)
    {
        $this->parameters[$key] = $value;
        return $this;
    }

    /**
    *   unset
    *
    *   @param string $key
    *   @return $this
    */
    public function unset(string $key)
    {
        unset($this->parameters[$key]);
        return $this;
    }

    /**
    *   all
    *
    *   @return string[]
    */
    public function all(): array
    {
        return $this->parameters;
    }

    /**
    *   __toString
    *
    *   @return string
    */
    public function __toString()
    {
        $parameters = [];
        array_walk(
            $this->parameters,
            function ($val, $key) use (&$parameters) {
                $parameters[] = "{$key}={$val}";
            }
        );
        return implode('&', $parameters);
    }

    /**
    *   buildQuery
    *
    *   @return string
    */
    public function buildQuery(): string
    {
        return http_build_query($this->parameters, '', '&');
    }
}
