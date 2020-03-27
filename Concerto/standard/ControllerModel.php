<?php

/**
*   ControllerModel
*
*   @version 190610
*/

namespace Concerto\standard;

use InvalidArgumentException;
use Concerto\auth\authentication\AuthSession;
use Concerto\standard\Session;
use Concerto\standard\ArrayAccessObject;

class ControllerModel extends ArrayAccessObject
{
    /**
    *   名前空間(overwite)
    *
    *   @var string
    */
    protected $namespace = '';
    
    /**
    *   factory
    *
    *   @var object
    */
    protected $factory;
    
    /**
    *   authUser
    *
    *   @var  AuthUser
    */
    protected $authUser;
    
    /**
    *   global session
    *
    *   @var Session
    */
    protected $globalSession;
    
    /**
    *   local session
    *
    *   @var Session
    */
    protected $session;
    
    /**
    *   valid結果
    *
    *   @var array
    */
    protected $validError = [];
    
    /**
    *   __construct
    *
    *   @param object $factory
    */
    public function __construct($factory)
    {
        $this->factory = $factory;
        $this->globalSession = new Session();
        $this->session = new Session($this->namespace);
        $this->authUser = (new AuthSession('auth'))->get();
        
        //継承classでsession未使用時にcsrfが更新されない対策
        $x = $this->session->dummy;
        
        $this->init();
    }
    
    /**
    *   init
    *
    */
    private function init()
    {
        $exploded = explode('/', $_SERVER['REQUEST_URI']);
        $this->globalSession->cd_system = $exploded[1];
    }
    
    /**
    *   エラー情報
    *
    *   @return array
    **/
    public function getValidError()
    {
        $errors = [];
        foreach ($this->validError as $list) {
            $errors[] = key($list);
        }
        return $errors;
    }
}
