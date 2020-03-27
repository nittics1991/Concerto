<?php

/**
*   PC認証(ITC0028 or ITCU2xxxxx)
*
*   @version 170508
*/

namespace Concerto\auth;

use Concerto\auth\AuthConst;
use Concerto\standard\Server;

class AuthComputer
{
    /**
    *   Server
    *
    *   @var string
    */
    protected $server;
    
    /**
    *   REMOTE ADR
    *
    *   @var string
    */
    protected $ip;
    
    /**
    *   REMOTE ADR
    *
    *   @var string
    */
    protected $host;
    
    /**
    *   コンストラクタ
    *
    */
    public function __construct()
    {
        $this->ip = (Server::has('x-forwarded-for')) ?
            Server::get('x-forwarded-for') : Server::get('remote_addr');
        $this->host = gethostbyaddr($this->ip);
    }
    
    /**
    *   Validate
    *
    *   @return bool
    */
    public function isValid()
    {
        $host = strtoupper($this->host);
        $local_name = strtoupper($this->server['computername']);
        $regex = '\A(' . $local_name . '|ITCU2[0-9]{5})\z';
        
        return (mb_ereg_match($regex, $host));
    }
}
