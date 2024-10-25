<?php

/**
*   ControllerModel
*
*   @version 240826
*/

declare(strict_types=1);

namespace Concerto\standard;

use RuntimeException;
use Concerto\auth\authentication\{
    AuthSession,
    AuthUser,
    AuthUserInterface,
};
use Concerto\auth\Csrf;
use Concerto\standard\{
    ArrayAccessObject,
    Server,
    Session
};

/**
*   @template TValue
*   @extends ArrayAccessObject<TValue>
*/
class ControllerModel extends ArrayAccessObject
{
    /**
    *   @var string
    */
    protected string $namespace = '';

    /**
    *   @var object
    */
    protected object $factory;

    /**
    *   @var  AuthUserInterface
    */
    protected AuthUserInterface $authUser;

    /**
    *   @var Session<TValue>
    */
    protected Session $globalSession;

    /**
    *   @var Session<TValue>
    */
    protected Session $session;

    /**
    *   @var mixed[]
    */
    protected array $validError = [];

    /**
    *   __construct
    *
    *   @param object $factory
    */
    public function __construct(
        object $factory
    ) {
        $this->factory = $factory;
        $this->globalSession = new Session();
        $this->session = new Session($this->namespace);

        $authSession = new AuthSession('auth');

        if ($authSession->get() === null) {
            throw new RuntimeException(
                "authUser not defined"
            );
        }

        $this->authUser = $authSession->get();

        //継承classでsession未使用時にcsrfが更新されない対策
        $x = $this->session->dummy;

        $this->init();
    }

    /**
    *   init
    *
    *   @return void
    */
    private function init(): void
    {
        $exploded = explode('/', $_SERVER['REQUEST_URI'] ?? '');
        $this->globalSession->cd_system = $exploded[1] ?? '';
    }

    /**
    *   エラー情報
    *
    *   @return mixed[]
    */
    public function getValidError(): array
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
    *   @return void
    */
    public function redirect(): void
    {
        if (method_exists($this->factory, 'getPost')) {
            $post = $this->factory->getPost();

            if (isset($post->token)) {
                Csrf::remove($post->token);
            }
        }

        header(
            'Location:' . Server::getRequestSelfUrl(),
            true,
            303
        );

        die;
    }
}
