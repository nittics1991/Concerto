<?php

/**
*   AuthSession
*
*   @version 221201
*/

declare(strict_types=1);

namespace Concerto\auth\authentication;

use Concerto\auth\authentication\AuthUserInterface;
use Concerto\cache\SessionCache;
use Psr\SimpleCache\CacheInterface;

class AuthSession
{
    /**
    *   @var string
    */
    protected string $keyName = 'authUser';

    /**
    *   @var CacheInterface
    */
    protected CacheInterface $session;

    /**
    *   __construct
    *
    *   @param string $namespace
    */
    public function __construct(
        string $namespace
    ) {
        $this->session = new SessionCache($namespace);
    }

    /**
    *   ログイン確認
    *
    *   @return bool
    */
    public function logined(): bool
    {
        return !empty($this->session->get($this->keyName));
    }

    /**
    *   取得
    *
    *   @return ?AuthUserInterface
    */
    public function get(): ?AuthUserInterface
    {
        if (!$this->logined()) {
            return null;
        }

        $content = $this->session->get($this->keyName);

        if (!is_string($content)) {
            return null;
        }

        $authUser =  unserialize(
            $content,
        );

        return $authUser instanceof AuthUserInterface ?
            $authUser : null;
    }

    /**
    *   保存
    *
    *   @param AuthUserInterface $authUser
    *   @return static
    */
    public function save(
        AuthUserInterface $authUser
    ): static {
        $this->session->set(
            $this->keyName,
            serialize($authUser)
        );

        return $this;
    }

    /**
    *   削除
    *
    *   @return static
    */
    public function delete(): static
    {
        $this->session->delete($this->keyName);

        return $this;
    }
}
