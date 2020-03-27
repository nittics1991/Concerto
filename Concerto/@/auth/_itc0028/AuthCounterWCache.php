<?php

/**
*   login counter(WinCache)
*
*   @version 160822
**/

namespace Concerto\auth;

class AuthCounterWCache implements AuthCounterInterface
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
    *   コンストラクタ
    *
    *   @param string ユーザID
    *   @param integer リトライカウンタ制限回数
    *   @param integer タイムアウト時間
    */
    public function __construct($target, $count = 5, $timeout = 15)
    {
        session_start();
        $this->target = $this->createKey($target);
        $this->count = $count;
        $this->timeout = $timeout;
    }
    
    /**
    *   バリデート
    *
    *   @return bool
    */
    public function isValid()
    {
        if (is_int(wincache_ucache_get($this->target))) {
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
                $_SESSION[$this->namespace][$this->contents][] = time();
                
                if (count($_SESSION[$this->namespace][$this->contents]) > $this->count) {
                    $this->setLimit();
                }
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
        wincache_ucache_add($this->target, time(), $this->timeout * 60);
        unset($_SESSION[$this->namespace][$this->contents]);
        return $this;
    }
    
    /**
    *   リトライ制限削除
    *
    *   @return object $this
    */
    protected function removeLimit()
    {
        wincache_ucache_delete($this->target);
        unset($_SESSION[$this->namespace][$this->contents]);
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
}
