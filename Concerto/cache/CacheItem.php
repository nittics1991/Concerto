<?php

/**
 *   CacheItem
 *
 * @version 210615
 */

declare(strict_types=1);

namespace Concerto\cache;

use DateTimeImmutable;
use DateTimeInterface;
use DateInterval;
use Concerto\cache\InvalidArgumentException;
use Psr\Cache\CacheItemInterface;

class CacheItem implements CacheItemInterface
{
    /**
     *   キー
     *
     * @var string
     */
    protected $key;

    /**
     *   値
     *
     * @var mixed
     */
    protected $value;

    /**
     *   キャッシュヒット
     *
     * @var mixed
     */
    protected $isHit;

    /**
     *   キャッシュ期間(sec or UNIX time)
     *
     * @var int
     */
    protected $ttl;

    /**
     *   初期有効期間(sec)
     *
     * @var int
     */
    protected $defaultlifetime = 86400;

    /**
     *   __construct
     *
     * @param string $key
     * @param mixed  $value
     * @param int    $ttl   sec | unix time
     * @param bool   $isHit キャッシュヒット
     */
    public function __construct(
        string $key,
        $value,
        ?int $ttl = null,
        bool $isHit = false
    ) {
        $this->key = $key;
        $this->value = $value;
        $this->isHit = $isHit;
        $this->ttl = (is_null($ttl)) ? $this->defaultlifetime : $ttl;
    }

    /**
     *   キー取得
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key ?? '';
    }

    /**
     *   値取得
     *
     * @return mixed
     */
    public function get(): mixed
    {
        return $this->value;
    }

    /**
     *   キャッシュヒット
     *
     * @return bool
     */
    public function isHit(): bool
    {
        return $this->isHit;
    }

    /**
     *   値設定
     *
     * @param  mixed $value
     * @return static
     */
    public function set(mixed $value): static
    {
        $this->value = $value;
        return $this;
    }

    /**
     *   有効期限設定(日付指定)
     *
     * @param  DateTimeInterface|null $expiration
     * @return static
     * @throw  InvalidArgumentException
     */
    public function expiresAt(?\DateTimeInterface $expiration): static
    {
        if ($expiration == null) {
            $this->ttl = $this->defaultlifetime;
        } elseif ($expiration instanceof DateTimeInterface) {
            $this->ttl = (int)$expiration->format('U') - time();
        } else {
            throw new InvalidArgumentException(
                "expiration must be DateTimeInterface"
            );
        }
        return $this;
    }

    /**
     *   有効期限設定(日付間隔指定)
     *
     * @param  DateInterval|int|null $time
     * @return $this
     * @throw  InvalidArgumentException
     */
    public function expiresAfter(int | \DateInterval | null $time): static
    {
        if ($time == null) {
            $this->ttl = $this->defaultlifetime;
        } elseif (is_int($time)) {
            $this->ttl = $time;
        } elseif ($time instanceof DateInterval) {
            $dt = DateTimeImmutable::createFromFormat(
                'U',
                (string)time()
            );

            if ($dt === false) {
                throw new InvalidArgumentException(
                    "expiration must be DateInterval or integer"
                );
            }

            $this->ttl = (int)(
                    $dt->add($time)->format('U')
                ) - time();
        } else {
            throw new InvalidArgumentException(
                "expiration must be DateInterval or integer"
            );
        }
        return $this;
    }

    /**
     *   キャッシュ期間取得
     *
     *  @return int
     */
    public function getExpiry()
    {
        return $this->ttl;
    }
}
