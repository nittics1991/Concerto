<?php

/**
*   DigestAuthentication
*
*   @version 240709
*/

declare(strict_types=1);

namespace Concerto\auth\authentication;

use RuntimeException;
use Concerto\auth\authentication\AuthUserRepositoryInterface;
use Concerto\hashing\{
    RandomNumberGenaratorInterface,
    StandardRandomNumberGenarator
};

class DigestAuthentication implements
    AuthInterface,
    HttpAuthenticationInterface
{
    use HttpAuthenticationTraitImpl;

    /**
    *   algorithm
    */
    public const MD5 = 'md5';

    public const SHA256 = 'sha256';

    public const SHA512 = 'sha512';

    /**
    *   @var string
    */
    private string $algorithm = self::MD5;

    /**
    *   @var ?RandomNumberGenaratorInterface
    */
    private ?RandomNumberGenaratorInterface $randomNumberGenerator;

    /**
    *   @var AuthUserRepositoryInterface
    */
    private AuthUserRepositoryInterface $repository;

    /**
    *   @var string
    */
    private string $realm;

    /**
    *   __construct
    *
    *   @param AuthUserRepositoryInterface $repository
    *   @param string $realm
    */
    public function __construct(
        AuthUserRepositoryInterface $repository,
        string $realm
    ) {
        $this->repository = $repository;

        $this->realm = $realm;
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
/*
        $user = $user ?? (
            $_SERVER['PHP_AUTH_USER'] ?? null
        );

        $password = $password ?? (
            $_SERVER['PHP_AUTH_PW'] ?? null
        );

        if (!isset($user)) {
            return false;
        }
*/

        if (!isset($_SERVER['PHP_AUTH_DIGEST'])) {
            return false;
        }

        return $this->verifyRequest();
    }

    /**
    *   verifyRequest
    *
    *   @return bool
    */
    private function verifyRequest(): bool
    {
        $parameters = $this->parseRequest();

        $authUser = $this->repository->findByUserId(
            $parameters['username']
        );

        if (
            !isset($authUser) ||
            $authUser->getId() === null ||
            $authUser->getPassword() === null
        ) {
            return false;
        }

        return $this->validate(
            $authUser->getId(),
            $authUser->getPassword(),
            $parameters
        );
    }

    /**
    *   parseRequest
    *
    *   @return string[]
    */
    private function parseRequest(): array
    {
        $parameters = [
            'response' => 1,
            'username' => 1,
            'uri' => 1,
            'qop' => 1,
            'cnonce' => 1,
            'nc' => 1,
            'nonce' => 1,
        ];

        $data = [];

        $keys = implode('|', array_keys($parameters));

        preg_match_all(
            '@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@',
            $_SERVER['PHP_AUTH_DIGEST'],
            $matches,
            PREG_SET_ORDER
        );

        foreach ($matches as $m) {
            $data[$m[1]] = $m[3] ? $m[3] : $m[4];
            unset($parameters[$m[1]]);
        }

        if (!empty($parameters)) {
            throw new RuntimeException(
                "invalid request parameter"
            );
        }

        return $data;
    }

    /**
    *   validate
    *
    *   @param string $user
    *   @param string $password
    *   @param array $parameters
    *   @return bool
    */
    private function validate(
        string $user,
        string $password,
        array $parameters
    ): bool {
        $param1 = hash(
            $this->algorithm,
            "{$user}:{$this->realm}:{$password}"
        );

        $param2 = hash(
            $this->algorithm,
            "{$_SERVER['REQUEST_METHOD']}:{$parameters['uri']}"
        );

        $hash = hash(
            $this->algorithm,
            "{$param1}:{$parameters['nonce']}:{$parameters['nc']}:" .
            "{$parameters['cnonce']}:{$parameters['qop']}:{$param2}"
        );

        return $parameters['response'] === $hash;
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

        $response = 'WWW-Authenticate: Digest ';

        $response .= "realm=\"{$this->realm}\",";

        $response .= "qop=\"auth\",";

        $generator = isset($this->randomNumberGenerator) ?
            $this->randomNumberGenerator :
            new StandardRandomNumberGenarator();

        $nonce = $generator->generate();

        $response .= "nonce=\"{$nonce}\",";

        $opaque = hash(
            $this->algorithm,
            $this->realm
        );

        $response .= "opaque=\"{$opaque}\"";

        header('HTTP/1.0 401 Unauthorized');

        header($response);

        die;
    }

    /**
    *   setAlgorithm
    *
    *   @param string $algorithm
    *   @return static
    */
    public function setAlgorithm(
        string $algorithm
    ): static {
        $this->algorithm = $algorithm;

        return $this;
    }

    /**
    *   setARandomNumberGenarator
    *
    *   @param RandomNumberGenaratorInterface $generator
    *   @return static
    */
    public function setARandomNumberGenarator(
        RandomNumberGenaratorInterface $generator
    ): static {
        $this->randomNumberGenerator = $generator;

        return $this;
    }
}
