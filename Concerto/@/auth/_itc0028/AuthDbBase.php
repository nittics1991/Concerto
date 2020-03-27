<?php

/**
*   データベース認証
*
*   @version 170714
*/

namespace Concerto\auth;

use DateInterval;
use DateTime;
use DateTimeInterface;
use DateTimeImmutable;
use Concerto\auth\AuthConst;
use Concerto\auth\AuthDbBaseFactoryInterface;
use Concerto\auth\AuthInterface;
use Concerto\Validate;

abstract class AuthDBBase
{
    /**
    *   ログ削除日数
    *
    *   @var integer
    */
    protected $logDay = 9999;
    
    /**
    *   パスワード有効日数
    *
    *   @var integer
    */
    protected $expirationDay = 9999;
    
    /**
    *   object
    *
    *   @var object
    */
    protected $loginInf;
    protected $loginInfData;
    protected $mstTanto;
    protected $mstTantoData;
    protected $session;
    
    /**
    *   コンストラクタ
    *
    *   @param AuthDbFactoryInterface
    */
    public function __construct(
        AuthDbBaseFactoryInterface $factory,
        array $config = []
    ) {
        $this->loginInf = $factory->getLoginInf();
        $this->loginInfData = $factory->getLoginInfData();
        $this->mstTanto = $factory->getMstTanto();
        $this->mstTantoData = $factory->getMstTantoData();
        $this->session = $factory->getSession();
        
        $this->setConfig($config);
    }
    
    /**
    *   config設定
    *
    *   @param array
    */
    protected function setConfig(array $config)
    {
        $property = ['logDay', 'expirationDay'];
        
        foreach ($property as $prop) {
            if (isset($config[$prop])) {
                $valid =  'isValid' . ucfirst($prop);
                if ($this->$valid($config[$prop])) {
                    $this->$prop = $config[$prop];
                }
            }
        }
    }
    
    /**
    *   validate(logDay)
    *
    *   @param array
    *   @return bool
    */
    protected function isValidLogDay($val)
    {
        return is_int($val) && ($val >= 0);
    }
    
    /**
    *   validate(expirationDay)
    *
    *   @param array
    *   @return bool
    */
    protected function isValidExpirationDay($val)
    {
        return is_int($val) && ($val >= 0);
    }
    
    /**
    *   パスワード期限確認
    *
    *   @param DateTimeInterface パスワード最終更新日
    *   @return bool
    */
    public function confirmPasswordExpiration(DateTimeInterface $lastUpdate)
    {
        try {
            $expiration = $lastUpdate->add(
                new DateInterval("P{$this->expirationDay}D")
            );
            if ($expiration === false) {
                return false;
            }
            
            $today = new DateTimeImmutable();
            
            if ($expiration < $today) {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    
    /**
    *   ログイン
    *
    *   @param string id
    *   @param string パスワード
    *   @return integer
    */
    abstract public function login($user, $password);
    
    /**
    *   ログアウト
    */
    public function logout()
    {
        unset($this->session->user);
    }
    
    /**
    *   ログイン履歴登録・旧履歴削除
    *
    *   @param string id
    *   @param string ユーザ名
    *   @return bool
    *   @throws RuntimeException
    */
    protected function setLoginLog($id, $name)
    {
        $this->loginInfData->ins_date = date('Ymd His');
        $this->loginInfData->cd_tanto = $id;
        $this->loginInfData->nm_tanto = $name;
        
        if (!$this->loginInfData->isValid()) {
            return false;
        }
        $this->loginInf->insert(array($this->loginInfData));
        $this->loginInf->deletePastDate($this->logDay);
        return true;
    }
    
    /**
    *   マジックメソッド
    *
    *   @param string id
    *   @return string ユーザ名 or null
    */
    public function __get($name)
    {
        if (($name == 'user') && (isset($this->session->$name))) {
            return $this->session->$name;
        }
        return null;
    }
}
