<?php

/**
*   BasicAuthentication
*
*   @version 240709
*/

declare(strict_types=1);

namespace Concerto\auth\authentication;

use RuntimeException;
use Concerto\auth\authentication\{
    AuthInterface,
    HttpAuthenticationTraitImpl,
    HttpAuthenticationInterface,
};

class BasicAuthentication implements
    AuthInterface,
    HttpAuthenticationInterface
{
    use HttpAuthenticationTraitImpl;

    /**
    *   @var AuthInterface
    */
    private AuthInterface $ahthGate;

    /**
    *   @var string[]
    */
    private array $headers = [];

    /**
    *   __construct
    *
    *   @param AuthInterface $ahthGate
    */
    public function __construct(
        AuthInterface $ahthGate
    ) {
        $this->ahthGate = $ahthGate;
    }

    /**
    *   {inheritDoc}
    *
    *   @param ?string $user
    *   @param ?string $password
    */
    public function login(
        ?string $user = null,
        ?string $password = null,
    ): bool {
        $user = $user ?? (
            $_SERVER['PHP_AUTH_USER'] ?? null
        );

        $password = $password ?? (
            $_SERVER['PHP_AUTH_PW'] ?? null
        );

        if (!isset($user)) {
            return false;
        }

        return $this->ahthGate->login(
            $user,
            $password,
        );
    }

    /**
    *   requestCredential
    */
    public function requestCredential()
    {
        if (headers_sent()) {
            throw new RuntimeException(
                "already been sent headers"
            );
        }

        header('WWW-Authenticate: Basic');
        header('HTTP/1.0 401 Unauthorized');

        foreach ($this->headers as $name => $value) {
            header(
                implode(
                    ':',
                    [
                        $name,
                        strval(mb_ereg_replace(
                            '[\r\n]',
                            '',
                            $value,
                        )),
                    ],
                ),
            );
        }

        die;
    }

    /**
    *   addHeader
    *
    *   @param string $name
    *   @param string $value
    */
    public function addHeader(
        string $name,
        string $value,
    ) {
        $this->headers[$name] = $value;
    }
}
