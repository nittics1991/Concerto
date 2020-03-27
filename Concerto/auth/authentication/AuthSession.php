<?php

/**
*   AuthSession
*
*   @version 190520
*/

declare(strict_types=1);

namespace Concerto\auth\authentication;

use Concerto\auth\authentication\AuthUserInterface;
use Concerto\cache\SessionCache;

class AuthSession
{
    /**
    *   keyName
    *
    *   @var string
    **/
    protected $keyName = 'authUser';
    
    /**
    *   session
    *
    *   @var SessionCache
    **/
    protected $session;
    
    /**
    *   __construct
    *
    *   @param string $namespace
    **/
    public function __construct(string $namespace)
    {
        $this->session = new SessionCache($namespace);
    }
    
    /**
    *   ログイン確認
    *
    *   @return bool
    **/
    public function logined(): bool
    {
        return !empty($this->session->get($this->keyName));
    }
    
    /**
    *   取得
    *
    *   @return ?AuthUserInterface
    **/
    public function get(): ?AuthUserInterface
    {
        if (!$this->logined()) {
            return null;
        }
        return unserialize(
            $this->session->get($this->keyName)
        );
    }
    
    /**
    *   保存
    *
    *   @param AuthUserInterface $authUser
    *   @return $this
    **/
    public function save(AuthUserInterface $authUser)
    {
        $this->session->set(
            $this->keyName,
            serialize($authUser)
        );
        return $this;
    }
    
    /**
    *   削除
    *
    *   @return $this
    **/
    public function delete()
    {
        $this->session->delete($this->keyName);
        return $this;
    }
}
