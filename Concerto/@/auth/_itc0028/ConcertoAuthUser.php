<?php

/**
*   ConcertoAuthUser
*
*   @ver 190610
*/

namespace Concerto\auth;

use Concerto\auth\authentication\AuthUser;

class ConcertoAuthUser extends AuthUser
{
    /**
    *   {necessary}
    *
    **/
    protected $propertyDefinitions = [
        'id', 'password',
        'unifiedUserId', 'name',
        'section',
        'kengenDb', 'kengenMac', 'kengenGpm',
        'input_pass',   //旧画面用
    ];
}
