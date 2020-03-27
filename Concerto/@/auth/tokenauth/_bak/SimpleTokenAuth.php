<?php

/**
*   SimpleTokenAuth
*
*   @ver 190903
*/

namespace Concerto\auth\tokenauth;

use Psr\SimpleCache\CacheInterface;
use Concerto\hashing\RandomNumberGenaratorInterface;

class SimpleTokenAuth
{
    /**
    *   realm
    *
    *   @var string
    */
    protected $realm = 'ConcertoWebApi';
    
    /**
    *   cache
    *
    *   @var CacheInterface
    */
    protected $cache;
    
    /**
    *   generator
    *
    *   @var RandomNumberGenaratorInterface
    */
    protected $generator;
    
    /**
    *   ttl
    *
    *   @var int
    */
    protected $ttl;
    
    /**
    *   __construct
    *
    *   @param CacheInterface $cache
    *   @param RandomNumberGenaratorInterface $generator
    *   @param int $ttl
    **/
    public function __construct(
        CacheInterface $cache,
        RandomNumberGenaratorInterface $generator,
        int $ttl = 60 * 15
    ) {
        $this->cache = $cache;
        $this->generator = $generator;
        $this->ttl = $ttl;
    }
    
    /**
    *   login
    *
    *   @return mixed
    **/
    public function login()
    {
        if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = $this->notHasAuthorizationHeader();
            $this->responce($headers);
        }
        
        $token = $this->parseRequest();
        
        if ($token == '') {
            $headers = $this->notHasBearerToken();
            $this->responce($headers);
        }
        
        if (!$this->cache->has($token)) {
            $headers = $this->invalidToken();
            $this->responce($headers);
        }
        $this->cache->delete($token);
        return;
    }
    
    /**
    *   response
    *
    *   @param array $headers
    **/
    private function response(array $headers)
    {
        if (headers_sent()) {
            throw new RuntimeException(
                "already been sent headers"
            );
        }
        
        foreach ($headers as $header) {
            header($header);
        }
    }
        exit;
}
    
    /**
    *   parseRequest
    *
    *   @return string
    **/
private function parseRequest()
{
    $tokens = mb_split(' ', trim($_SERVER['HTTP_AUTHORIZATION']);
        
    for ($i = 0; $i < count($tokens); $i++) {
        if (mb_strtolower(trim($tokens[$i])) == 'bearer') {
            break;
        }
    }
        
    for (++$i; $i < count($tokens); $i++) {
        if (mb_strlen(trim($tokens[$i])) > 0) {
            return base64_decode(trim($tokens[$i]));
        }
    }
    return '';
}
    
    /**
    *   generatorToken
    *
    *   @param int $ttl
    *   @return string
    **/
private function generatorToken(): string
{
    $token = $this->generator->generate();
    $this->cache->set($token, time(), $tttl);
    return base64_encode($token);
}
    
    /**
    *   notHasAuthorizationHeader
    *
    *   @return array
    **/
private function notHasAuthorizationHeader()
{
    $token = $this->generatorToken();
        
    $headers[] = 'HTTP/1.1 401 Unauthorized';
    $headers[] = "WWW-Authenticate: Bearer {$token}" .
        " realm=\"{$this->realm}\"";
    return $headers;
}
    
    /**
    *   notHasBearerToken
    *
    *   @return array
    **/
private function notHasBearerToken()
{
    $token = $this->generatorToken();
        
    $headers[] = 'HTTP/1.1 400 Bad Request';
    $headers[] = "WWW-Authenticate: Bearer {$token}" .
        " realm=\"{$this->realm}\"," .
        " error=\"invalid_request\"";
    return $headers;
}
    
    /**
    *   invalidToken
    *
    *   @return array
    **/
private function invalidToken()
{
    $token = $this->generatorToken();
        
    $headers[] = 'HTTP/1.1 401 Unauthorized';
    $headers[] = "WWW-Authenticate: Bearer {$token}" .
        " realm=\"{$this->realm}\"," .
        " error=\"invalid_token\"";
    return $headers;
}
    
    /**
    *   realm
    *
    *   @param string $token
    *   @return $this
    **/
private function realm(string $token)
{
    $this->realm = $token;
    return $this
}
}
