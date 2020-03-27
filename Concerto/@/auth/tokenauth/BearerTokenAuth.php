<?php

/**
*   BearerTokenAuth
*
*   @ver 190903
*/

namespace Concerto\auth\tokenauth;

use Concerto\auth\tokenauth\TokenAuthMatcherInterface;

class BearerTokenAuth
{
    /**
    *   realm
    *
    *   @var string
    */
    protected $realm = 'ConcertoWebApi';
    
    /**
    *   matcher
    *
    *   @var TokenAuthMatcherInterface
    */
    protected $matcher;
    
    /**
    *   __construct
    *
    *   @param TokenAuthMatcherInterface $matcher
    **/
    public function __construct(
        TokenAuthMatcherInterface $matcher
    ) {
        $this->matcher = $matcher;
    }
    
    /**
    *   login
    *
    *   @return ?string
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
        
        if (!$this->matcher->match($token)) {
            $headers = $this->invalidToken();
            $this->responce($headers);
        }
        return $token;
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
        exit;
    }
    
    /**
    *   parseRequest
    *
    *   @return string
    **/
    private function parseRequest()
    {
        $tokens = mb_split(' ', trim($_SERVER['HTTP_AUTHORIZATION']));
        
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
    *   notHasAuthorizationHeader
    *
    *   @return array
    **/
    private function notHasAuthorizationHeader()
    {
        $headers[] = 'HTTP/1.1 401 Unauthorized';
        $headers[] = "WWW-Authenticate: Bearer realm=\"{$this->realm}\"";
        return $headers;
    }
    
    /**
    *   notHasBearerToken
    *
    *   @return array
    **/
    private function notHasBearerToken()
    {
        $headers[] = 'HTTP/1.1 400 Bad Request';
        $headers[] = "WWW-Authenticate: Bearer realm=\"{$this->realm}\"," .
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
        $headers[] = 'HTTP/1.1 401 Unauthorized';
        $headers[] = "WWW-Authenticate: Bearer realm=\"{$this->realm}\"," .
            " error=\"invalid_token\"";
        return $headers;
    }
    
    /**
    *   realm
    *
    *   @param string $token
    *   @return $this
    **/
    public function realm(string $token)
    {
        $this->realm = $token;
        return $this;
    }
}
