<?php

/**
*   StandardCookieCache
*
*   @version 200918
*/

declare(strict_types=1);
declare(strict_types=1);

namespace Concerto\standard;

use Concerto\cache\CookieCache;
use Concerto\encrypt\StandardEncrypter;

class StandardCookieCache extends CookieCache
{
    /**
    *   cncrypt_key
    *
    *   @var string
    */
    private string $cncrypt_key;

    /**
    *   __construct
    *
    *   @param string $namespace
    *   @param mixed[] $options
    *   @param ?string $cncrypt_key
    */
    public function __construct(
        string $namespace,
        array $options = [],
        ?string $cncrypt_key = null
    ) {
        $this->namespace = $namespace;
        $this->options = array_merge($this->options, $options);
        $this->cncrypt_key = $cncrypt_key ??
            mb_substr(StandardCookieCache::class, 0, 32, '8bit');
    }

    /**
    *   {inherit}
    */
    public function get($key, $default = null)
    {
        $encrypter = new StandardEncrypter(
            $this->cncrypt_key
        );

        $data = parent::get($key, $default);

        if (is_null($data)) {
            return $default;
        }

        return unserialize(
            $encrypter->decrypt($data)
        );
    }

    /**
    *   {inherit}
    */
    public function set($key, $value, $ttl = null)
    {
        $encrypter = new StandardEncrypter(
            $this->cncrypt_key
        );

        return parent::set(
            $key,
            $encrypter->encrypt(serialize($value)),
            $ttl ?? time() + 60 * 60 * 24 * 365 * 10
        );
    }

    /**
     *   {inherit}
     */
    public function delete($key)
    {
        return parent::set($key, '', 1);
    }
}
