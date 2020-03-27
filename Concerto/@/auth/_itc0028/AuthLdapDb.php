<?php

/**
*   LDAP&データベース認証
*
*   @version 190730
*/

namespace Concerto\auth;

use RuntimeException;
use Concerto\auth\AuthConst;
use Concerto\auth\AuthDBBase;
use Concerto\auth\AuthDbBaseFactoryInterface;
use Concerto\auth\AuthInterface;
use Concerto\Validate;

class AuthLdapDB extends AuthDBBase implements AuthInterface
{
    /**
    *   AuthDb
    *
    *   @var string
    */
    const LDAP_SERVER = "ldap://tsb-sv203.toshiba.local";
    
    /**
    *   ログイン
    *
    *   @param string id
    *   @param string パスワード
    *   @return integer
    *   @throws RuntimeException
    */
    public function login($user = null, $password = null)
    {
        if (!empty($this->session->user)) {
            return AuthConst::AUTHENTICATED;
        }
        
        if (empty($user) || empty($password)) {
            return AuthConst::DATAEMPTY;
        }
        
        if (!mb_ereg_match('^[0-9a-zA-Z]{8}$', $user)) {
            return AuthConst::FAILURE;
        }
        
        $ans = AuthConst::FAILURE;
        $this->mstTantoData->username = $user;
        
        if ($this->mstTantoData->isValid() && Validate::isAscii($password, 1)) {
            $result = $this->mstTanto->select($this->mstTantoData);
            $data = $result[0] ?? null;
            
            if (
                !empty($data->cd_tanto)
                && (trim($data->kb_group) != "")
                && (trim($data->mail_add) != "")
            ) {
                $suffix = '@toshiba.local';
                
                if (($connect = ldap_connect(self::LDAP_SERVER)) == false) {
                    throw new RuntimeException("ldap connect error");
                }
                
                if (ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3) == false) {
                    throw new RuntimeException("ldap opt protocol version set error");
                }
                
                if (ldap_set_option($connect, LDAP_OPT_REFERRALS, 0) == false) {
                    throw new RuntimeException("ldap opt referrals set error");
                }
                
                if (@ldap_bind($connect, $user . $suffix, $password) === true) {
                    $this->session->changeID();
                    $this->session->user = $data->cd_tanto;
                    $this->setLoginLog($data->cd_tanto, $data->nm_tanto);
                    $this->session->user = $data->cd_tanto;
                    $ans = AuthConst::SUCCESS;
                }
            }
        }
        return $ans;
    }
}
