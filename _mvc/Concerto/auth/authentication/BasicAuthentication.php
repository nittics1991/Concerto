<?php

/**
*   BasicAuthentication
*
*   @version 210903
*/

declare(strict_types=1);

namespace Concerto\auth\authentication;

use RuntimeException;

class BasicAuthentication
{
    /**
    *   ahthGate
    *
    *   @var AuthInterface
    */
    private $ahthGate;

    /**
    *   basicResponce
    *
    *   @var ?callable
    */
    private $basicResponce;

    /**
    *   __construct
    *
    *   @param AuthInterface $ahthGate
    */
    public function __construct(AuthInterface $ahthGate)
    {
        $this->ahthGate = $ahthGate;
    }

    /**
    *   login
    *
    *   @return mixed
    */
    public function login()
    {
        if (
            isset($_SERVER['PHP_AUTH_USER']) &&
            $this->ahthGate->login(
                $_SERVER['PHP_AUTH_USER'],
                $_SERVER['PHP_AUTH_PW']
            )
        ) {
            return;
        }
        return $this->response();
    }

    /**
    *   response
    *
    *   @return mixed
    */
    private function response()
    {
        if (headers_sent()) {
            throw new RuntimeException(
                "already been sent headers"
            );
        }

        if ($this->basicResponce !== null) {
            return call_user_func($this->basicResponce);
        }

        header('WWW-Authenticate: Basic');
        header('HTTP/1.0 401 Unauthorized');
        exit;
    }

    /**
    *   setResponse
    *
    *   @param callable $callback
    *   @return $this
    */
    public function setResponse(callable $callback)
    {
        $this->basicResponce = $callback;
        return $this;
    }
}
