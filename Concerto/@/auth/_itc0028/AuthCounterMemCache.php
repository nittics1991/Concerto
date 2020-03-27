<?php

/**
*   login counter(MemcachedServer by memcache)
*
*   @version 160930
**/

namespace Concerto\auth;

use Memcache;
use RuntimeException;

class AuthCounterMemCache implements AuthCounterInterface
{
    /**
    *   SESSION名前空間
    *
    *   @var string
    */
    protected $namespace = 'auth';
    
    /**
    *   SESSION名
    *
    *   @var string
    */
    protected $contents = 'auth_counter';
    
    /**
    *   ユーザID
    *
    *   @var string
    */
    protected $target;
    
    /**
    *   リトライカウンタ制限回数
    *
    *   @var string
    */
    protected $count;
    
    /**
    *   タイムアウト時間
    *
    *   @var string
    */
    protected $timeout;
    
    /**
    *   キャッシュ
    *
    *   @var Memcache
    */
    protected $cache;
    
    /**
    *   コンストラクタ
    *
    *   @param string ユーザID
    *   @param integer リトライカウンタ制限回数
    *   @param integer タイムアウト時間
    *   @param string URI
    *   @param integer port
    */
    public function __construct(
        $target,
        $count = 5,
        $timeout = 15,
        $server = '127.0.0.1',
        $port = 11211
    ) {
        $this->target = $this->createKey($target);
        $this->count = $count;
        $this->timeout = $timeout;
        $this->connect($server, $port);
    }
    
    /**
    *   memcached接続
    *
    *   @param string URI
    *   @param integer port
    *   @throws RuntimeException
    */
    protected function connect($server, $port)
    {
        $this->cache = new Memcache();
        if ($this->cache->addServer($server, $port) === false) {
            throw new RuntimeException("add server error:{$server}:{$port}");
        }
        
        if ($this->cache->connect($server, $port) == false) {
            throw new RuntimeException("connect error:{$server}:{$port}");
        }
    }
    
    /**
    *   バリデート
    *
    *   @return bool
    */
    public function isValid()
    {
        if (is_int($this->cache->get($this->target))) {
            return false;
        }
        return true;
    }
    
    /**
    *   ログイン記録
    *
    *   @param integer 認証結果
    */
    public function log($param)
    {
        switch ($param) {
            case AuthConst::SUCCESS:
                $this->removeLimit();
                break;
            case AuthConst::FAILURE:
            case AuthConst::DATAEMPTY:
                $this->sessionStart();
                $_SESSION[$this->namespace][$this->contents][] = time();
                if (
                    count($_SESSION[$this->namespace][$this->contents])
                    > $this->count
                ) {
                    $this->setLimit();
                }
                session_write_close();
                break;
        }
    }
    
    /**
    *   リトライ制限登録
    *
    *   @return object $this
    */
    protected function setLimit()
    {
        $this->cache->set($this->target, time(), null, $this->timeout * 60);
        $this->sessionStart();
        unset($_SESSION[$this->namespace][$this->contents]);
        session_write_close();
        return $this;
    }
    
    /**
    *   リトライ制限削除
    *
    *   @return object $this
    */
    protected function removeLimit()
    {
        $this->cache->delete($this->target);
        $this->sessionStart();
        unset($_SESSION[$this->namespace][$this->contents]);
        session_write_close();
        return $this;
    }
    
    /**
    *   IP => IP名
    *
    *   @param string IP
    *   @return object $this
    */
    protected function createKey($target)
    {
        return mb_ereg_replace('\.', '_', $target);
    }
    
    /**
    *   sessionStart
    *
    */
    protected function sessionStart()
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            @session_start();
        }
    }
}
