<?php

/**
*   ControllerModel
*
*   @version 220302
*/

declare(strict_types=1);

namespace Concerto\standard;

use RuntimeException;
use Concerto\auth\authentication\{
    AuthSession,
    AuthUser
};
use Concerto\auth\Csrf;
use Concerto\standard\{
    ArrayAccessObject,
    Server,
    Session
};

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
    *   @var mixed[]
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

        $authUser = new AuthSession('auth');
        if ($authUser->get() === null) {
            throw new RuntimeException(
                "authUser not defined"
            );
        }

        $this->authUser = $authUser->get();

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
        $exploded = explode('/', $_SERVER['REQUEST_URI'] ?? '');
        $this->globalSession->cd_system = $exploded[1] ?? '';
    }

    /**
    *   エラー情報
    *
    *   @return mixed[]
    */
    public function getValidError()
    {
        $errors = [];
        foreach ($this->validError as $list) {
            $errors[] = key($list);
        }
        return $errors;
    }

    /**
    *   redirect
    *
    */
    public function redirect()
    {
        if (method_exists($this->factory, 'getPost')) {
            $post = $this->factory->getPost();

            if (isset($post->token)) {
                Csrf::remove($post->token);
            }
        }
        header('Location:' . Server::getRequestSelfUrl(), true, 303);
        die;
    }
}
